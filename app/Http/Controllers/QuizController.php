<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Course;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quizzes = Quiz::with(['questions' => function($query) {
            $query->withCount('options');
        }])
        ->where('is_published', true)
        ->get()
        ->map(function($quiz) {
            $quiz->questions_count = $quiz->questions->sum('options_count');
            return $quiz;
        });

        return view('quizzes.index', compact('quizzes'));
    }

    public function courseQuizzes(Course $course)
    {
        $quizzes = $course->quizzes()->where('is_published', true)->get();
        return view('quizzes.course', compact('course', 'quizzes'));
    }

    public function start(Quiz $quiz)
    {
        // Check if user is enrolled in the course
        if (!$quiz->course->isEnrolledBy(auth()->user())) {
            return redirect()->route('courses.show', $quiz->course)
                ->with('error', 'You must be enrolled in the course to take the quiz.');
        }

        // Check if there's an ongoing attempt
        $ongoingAttempt = QuizAttempt::where('user_id', auth()->id())
            ->where('quiz_id', $quiz->id)
            ->where('is_completed', false)
            ->first();

        if ($ongoingAttempt) {
            return view('quizzes.take', compact('quiz', 'ongoingAttempt'));
        }

        // Create new attempt
        $attempt = QuizAttempt::create([
            'user_id' => auth()->id(),
            'quiz_id' => $quiz->id,
            'started_at' => now()
        ]);

        return view('quizzes.take', compact('quiz', 'attempt'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $attempt = QuizAttempt::where('user_id', auth()->id())
            ->where('quiz_id', $quiz->id)
            ->where('is_completed', false)
            ->firstOrFail();

        $score = 0;
        $answers = $request->input('answers', []);

        foreach ($answers as $questionId => $answer) {
            $question = $quiz->questions()->findOrFail($questionId);
            $isCorrect = false;

            if ($question->type === 'multiple_choice') {
                $isCorrect = $question->options()
                    ->where('id', $answer)
                    ->where('is_correct', true)
                    ->exists();
            } elseif ($question->type === 'true_false') {
                $isCorrect = $answer === $question->correct_answer;
            }

            QuizAnswer::create([
                'quiz_attempt_id' => $attempt->id,
                'quiz_question_id' => $questionId,
                'quiz_option_id' => $question->type === 'multiple_choice' ? $answer : null,
                'answer' => $question->type === 'short_answer' ? $answer : null,
                'is_correct' => $isCorrect
            ]);

            if ($isCorrect) {
                $score += $question->points;
            }
        }

        $attempt->update([
            'score' => $score,
            'is_completed' => true,
            'completed_at' => now()
        ]);

        return redirect()->route('quizzes.results', $quiz)
            ->with('success', 'Quiz submitted successfully!');
    }

    public function results(Quiz $quiz)
    {
        $attempt = QuizAttempt::where('user_id', auth()->id())
            ->where('quiz_id', $quiz->id)
            ->where('is_completed', true)
            ->latest()
            ->firstOrFail();

        return view('quizzes.results', compact('quiz', 'attempt'));
    }

    public function review(Quiz $quiz)
    {
        $attempt = QuizAttempt::where('user_id', auth()->id())
            ->where('quiz_id', $quiz->id)
            ->where('is_completed', true)
            ->latest()
            ->firstOrFail();

        return view('quizzes.review', compact('quiz', 'attempt'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('quizzes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit' => 'nullable|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|in:multiple_choice,true_false,short_answer',
            'questions.*.points' => 'required|integer|min:1',
            'questions.*.options' => 'required_if:questions.*.type,multiple_choice|array',
            'questions.*.options.*.text' => 'required|string',
            'questions.*.options.*.is_correct' => 'required|boolean'
        ]);

        $quiz = Quiz::create([
            'course_id' => $validated['course_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'time_limit' => $validated['time_limit'],
            'passing_score' => $validated['passing_score'],
            'is_published' => false
        ]);

        foreach ($validated['questions'] as $questionData) {
            $question = $quiz->questions()->create([
                'text' => $questionData['text'],
                'type' => $questionData['type'],
                'points' => $questionData['points']
            ]);

            if ($questionData['type'] === 'multiple_choice') {
                foreach ($questionData['options'] as $optionData) {
                    $question->options()->create([
                        'text' => $optionData['text'],
                        'is_correct' => $optionData['is_correct']
                    ]);
                }
            }
        }

        return redirect()->route('courses.show', $quiz->course)
            ->with('success', 'Quiz created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quiz $quiz)
    {
        return view('quizzes.edit', compact('quiz'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit' => 'nullable|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'is_published' => 'boolean'
        ]);

        $quiz->update($validated);

        return redirect()->route('courses.show', $quiz->course)
            ->with('success', 'Quiz updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('courses.show', $quiz->course)
            ->with('success', 'Quiz deleted successfully!');
    }
}
