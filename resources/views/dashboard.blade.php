@extends('layouts.app')

@section('content')
<div class="container py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Welcome, {{ auth()->user()->name }}!</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Here's an overview of your learning journey.</p>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                        <i class="fas fa-book text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Enrolled Courses</h3>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $enrolledCourses->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Completed Courses</h3>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $completedCourses->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                        <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">In Progress</h3>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $inProgressCourses->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Enrolled Courses -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Your Courses</h2>
                </div>
                <div class="p-6">
                    @if($enrolledCourses->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">You haven't enrolled in any courses yet.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($enrolledCourses as $course)
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <h3 class="font-medium text-gray-900 dark:text-white">{{ $course->title }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Instructor: {{ $course->instructor->name }}</p>
                                    </div>
                                    <a href="{{ route('courses.show', $course) }}" class="btn btn-primary btn-sm">
                                        Continue Learning
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Recent Activity</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @if($recentQuizzes->isNotEmpty())
                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recent Quizzes</h3>
                                @foreach($recentQuizzes as $quiz)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg mb-2">
                                        <div>
                                            <h4 class="font-medium text-gray-900 dark:text-white">{{ $quiz->title }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $quiz->course->title }}</p>
                                        </div>
                                        <a href="{{ route('quizzes.take', $quiz) }}" class="btn btn-primary btn-sm">
                                            Take Quiz
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if($recentNotes->isNotEmpty())
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recent Notes</h3>
                                @foreach($recentNotes as $note)
                                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg mb-2">
                                        <h4 class="font-medium text-gray-900 dark:text-white">{{ $note->title }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($note->content, 100) }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if(auth()->user()->isInstructor())
            <!-- Instructor Section -->
            <div class="mt-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Instructor Dashboard</h2>
                
                <!-- Instructor Courses -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Your Courses</h3>
                    </div>
                    <div class="p-6">
                        @if($instructorCourses->isEmpty())
                            <p class="text-gray-600 dark:text-gray-400">You haven't created any courses yet.</p>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($instructorCourses as $course)
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                        <h4 class="font-medium text-gray-900 dark:text-white mb-2">{{ $course->title }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ Str::limit($course->description, 100) }}</p>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $course->enrollments->count() }} Students
                                            </span>
                                            <a href="{{ route('courses.edit', $course) }}" class="btn btn-primary btn-sm">
                                                Manage Course
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Instructor Quizzes -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Your Quizzes</h3>
                    </div>
                    <div class="p-6">
                        @if($instructorQuizzes->isEmpty())
                            <p class="text-gray-600 dark:text-gray-400">You haven't created any quizzes yet.</p>
                        @else
                            <div class="space-y-4">
                                @foreach($instructorQuizzes as $quiz)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div>
                                            <h4 class="font-medium text-gray-900 dark:text-white">{{ $quiz->title }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $quiz->course->title }}</p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('quizzes.edit', $quiz) }}" class="btn btn-outline btn-sm">
                                                Edit
                                            </a>
                                            <a href="{{ route('quizzes.results', $quiz) }}" class="btn btn-primary btn-sm">
                                                View Results
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
