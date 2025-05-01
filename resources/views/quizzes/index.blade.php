@extends('layouts.app')

@section('content')
<div class="container py-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Quizzes</h1>
            @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('quizzes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i>Create Quiz
                </a>
            @endif
        </div>

        @if($quizzes->isEmpty())
            <div class="text-center py-8">
                <p class="text-gray-600 dark:text-gray-400">No quizzes available at the moment.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($quizzes as $quiz)
                    <div class="card quiz-card">
                        <div class="card-body">
                            <h3 class="text-xl font-semibold mb-2">{{ $quiz->title }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $quiz->description }}</p>
                            
                            <div class="flex items-center justify-between">
                                <span class="badge bg-primary">
                                    {{ $quiz->questions_count }} Questions
                                </span>
                                <span class="text-sm text-gray-500">
                                    {{ $quiz->time_limit }} minutes
                                </span>
                            </div>

                            <div class="mt-4">
                                @if($quiz->is_published)
                                    <a href="{{ route('quizzes.start', $quiz) }}" class="btn btn-primary w-full">
                                        Start Quiz
                                    </a>
                                @else
                                    <span class="badge bg-warning">Draft</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection 