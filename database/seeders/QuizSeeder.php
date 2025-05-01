<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Course;
use App\Models\QuizQuestion;
use App\Models\QuizOption;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create a course for the quizzes
        $course = Course::firstOrCreate([
            'title' => 'Web Development Fundamentals',
            'description' => 'Learn the basics of web development including HTML, CSS, and JavaScript.',
            'instructor_id' => 1,
            'is_published' => true
        ]);

        // Create HTML Quiz
        $htmlQuiz = Quiz::create([
            'course_id' => $course->id,
            'title' => 'HTML Basics Quiz',
            'description' => 'Test your knowledge of HTML fundamentals including tags, attributes, and document structure.',
            'time_limit' => 15,
            'passing_score' => 70,
            'is_published' => true
        ]);

        // HTML Quiz Questions
        $questions = [
            [
                'question' => 'What does HTML stand for?',
                'options' => [
                    ['text' => 'Hyper Text Markup Language', 'is_correct' => true],
                    ['text' => 'High Tech Modern Language', 'is_correct' => false],
                    ['text' => 'Hyper Transfer Markup Language', 'is_correct' => false],
                    ['text' => 'Hyper Text Modern Language', 'is_correct' => false]
                ]
            ],
            [
                'question' => 'Which HTML tag is used for creating a hyperlink?',
                'options' => [
                    ['text' => '<link>', 'is_correct' => false],
                    ['text' => '<a>', 'is_correct' => true],
                    ['text' => '<href>', 'is_correct' => false],
                    ['text' => '<url>', 'is_correct' => false]
                ]
            ],
            [
                'question' => 'What is the correct HTML element for the largest heading?',
                'options' => [
                    ['text' => '<h1>', 'is_correct' => true],
                    ['text' => '<heading>', 'is_correct' => false],
                    ['text' => '<head>', 'is_correct' => false],
                    ['text' => '<h6>', 'is_correct' => false]
                ]
            ]
        ];

        foreach ($questions as $questionData) {
            $question = QuizQuestion::create([
                'quiz_id' => $htmlQuiz->id,
                'text' => $questionData['question']
            ]);

            foreach ($questionData['options'] as $optionData) {
                QuizOption::create([
                    'quiz_question_id' => $question->id,
                    'text' => $optionData['text'],
                    'is_correct' => $optionData['is_correct']
                ]);
            }
        }

        // Create CSS Quiz
        $cssQuiz = Quiz::create([
            'course_id' => $course->id,
            'title' => 'CSS Fundamentals Quiz',
            'description' => 'Test your understanding of CSS including selectors, properties, and layout techniques.',
            'time_limit' => 20,
            'passing_score' => 70,
            'is_published' => true
        ]);

        // CSS Quiz Questions
        $questions = [
            [
                'question' => 'What does CSS stand for?',
                'options' => [
                    ['text' => 'Computer Style Sheets', 'is_correct' => false],
                    ['text' => 'Cascading Style Sheets', 'is_correct' => true],
                    ['text' => 'Creative Style Sheets', 'is_correct' => false],
                    ['text' => 'Colorful Style Sheets', 'is_correct' => false]
                ]
            ],
            [
                'question' => 'Which property is used to change the background color?',
                'options' => [
                    ['text' => 'color', 'is_correct' => false],
                    ['text' => 'background-color', 'is_correct' => true],
                    ['text' => 'bgcolor', 'is_correct' => false],
                    ['text' => 'background', 'is_correct' => false]
                ]
            ],
            [
                'question' => 'How do you select an element with id "demo"?',
                'options' => [
                    ['text' => '.demo', 'is_correct' => false],
                    ['text' => '#demo', 'is_correct' => true],
                    ['text' => '*demo', 'is_correct' => false],
                    ['text' => 'demo', 'is_correct' => false]
                ]
            ]
        ];

        foreach ($questions as $questionData) {
            $question = QuizQuestion::create([
                'quiz_id' => $cssQuiz->id,
                'text' => $questionData['question']
            ]);

            foreach ($questionData['options'] as $optionData) {
                QuizOption::create([
                    'quiz_question_id' => $question->id,
                    'text' => $optionData['text'],
                    'is_correct' => $optionData['is_correct']
                ]);
            }
        }

        // Create JavaScript Quiz
        $jsQuiz = Quiz::create([
            'course_id' => $course->id,
            'title' => 'JavaScript Essentials Quiz',
            'description' => 'Test your knowledge of JavaScript programming including variables, functions, and DOM manipulation.',
            'time_limit' => 25,
            'passing_score' => 70,
            'is_published' => true
        ]);

        // JavaScript Quiz Questions
        $questions = [
            [
                'question' => 'Which of the following is not a JavaScript data type?',
                'options' => [
                    ['text' => 'String', 'is_correct' => false],
                    ['text' => 'Boolean', 'is_correct' => false],
                    ['text' => 'Float', 'is_correct' => true],
                    ['text' => 'Number', 'is_correct' => false]
                ]
            ],
            [
                'question' => 'How do you declare a variable in JavaScript?',
                'options' => [
                    ['text' => 'v myVar;', 'is_correct' => false],
                    ['text' => 'variable myVar;', 'is_correct' => false],
                    ['text' => 'let myVar;', 'is_correct' => true],
                    ['text' => 'var: myVar;', 'is_correct' => false]
                ]
            ],
            [
                'question' => 'Which method is used to add an element to the end of an array?',
                'options' => [
                    ['text' => 'push()', 'is_correct' => true],
                    ['text' => 'append()', 'is_correct' => false],
                    ['text' => 'add()', 'is_correct' => false],
                    ['text' => 'insert()', 'is_correct' => false]
                ]
            ]
        ];

        foreach ($questions as $questionData) {
            $question = QuizQuestion::create([
                'quiz_id' => $jsQuiz->id,
                'text' => $questionData['question']
            ]);

            foreach ($questionData['options'] as $optionData) {
                QuizOption::create([
                    'quiz_question_id' => $question->id,
                    'text' => $optionData['text'],
                    'is_correct' => $optionData['is_correct']
                ]);
            }
        }
    }
}
