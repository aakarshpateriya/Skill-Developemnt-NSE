<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query();

        // Search by title or description
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category') && $request->get('category') !== '') {
            $query->where('category', $request->get('category'));
        }

        // Filter by skill level
        if ($request->has('skill_level') && $request->get('skill_level') !== '') {
            $query->where('skill_level', $request->get('skill_level'));
        }

        $courses = $query->paginate(9);
        return view('courses.index', compact('courses'));
    }

    public function myCourses()
    {
        $courses = auth()->user()->enrolledCourses()
            ->withPivot('status', 'progress')
            ->get();

        return view('courses.my-courses', compact('courses'));
    }

    public function create()
    {
        return view('courses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'skill_level' => 'required|in:Beginner,Intermediate,Advanced',
            'category' => 'required|string|max:255'
        ]);

        try {
            Course::create($request->all());
            return redirect()->route('courses.index')->with('success', 'Course created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating course: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Course $course)
    {
        $course->load(['quizzes' => function($query) {
            $query->where('is_published', true);
        }]);

        $enrollment = $course->getEnrollmentForUser(auth()->user());
        $notes = $course->notes()->where('user_id', auth()->id())->get();

        return view('courses.show', compact('course', 'enrollment', 'notes'));
    }

    public function edit(Course $course)
    {
        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'skill_level' => 'required|in:Beginner,Intermediate,Advanced',
            'category' => 'required|string|max:255'
        ]);

        try {
            $course->update($request->all());
            return redirect()->route('courses.index')->with('success', 'Course updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating course: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Course $course)
    {
        try {
            $course->delete();
            return redirect()->route('courses.index')->with('success', 'Course deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting course: ' . $e->getMessage());
        }
    }

    public function enroll(Course $course)
    {
        if ($course->isEnrolledBy(auth()->user())) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'You are already enrolled in this course.');
        }

        try {
            auth()->user()->enrolledCourses()->attach($course->id, [
                'status' => 'in_progress',
                'progress' => 0
            ]);

            return redirect()->route('courses.show', $course)
                ->with('success', 'Successfully enrolled in the course!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error enrolling in course: ' . $e->getMessage());
        }
    }
}
