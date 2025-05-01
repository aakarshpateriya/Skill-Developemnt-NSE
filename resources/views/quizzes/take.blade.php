@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
@endsection

@section('content')
<div class="container py-8">
    <div class="max-w-4xl mx-auto">
        <div class="quiz-container">
            <div class="quiz-header">
                <div class="quiz-title">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $quiz->title }}</h2>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $quiz->description }}</p>
                </div>
                <div class="quiz-timer" id="timer">
                    <i class="fas fa-clock text-primary"></i>
                    <span id="time-remaining" class="text-lg font-semibold">Time Remaining: {{ $quiz->time_limit }} minutes</span>
                </div>
            </div>

            <div class="quiz-info">
                <div class="info-item">
                    <i class="fas fa-list-ol text-primary"></i>
                    <span>{{ $quiz->questions->count() }} Questions</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-star text-primary"></i>
                    <span>{{ $quiz->total_points }} Points</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-clock text-primary"></i>
                    <span>{{ $quiz->time_limit }} Minutes</span>
                </div>
            </div>

            <form action="{{ route('quizzes.submit', $quiz) }}" method="POST" id="quiz-form" class="mt-8">
                @csrf
                <div class="questions-container space-y-8">
                    @foreach($quiz->questions as $index => $question)
                        <div class="question-card">
                            <div class="question-header">
                                <span class="question-number">Question {{ $index + 1 }}</span>
                                <span class="question-points">{{ $question->points }} points</span>
                            </div>
                            <div class="question-text">
                                {{ $question->text }}
                            </div>
                            <div class="options-list">
                                @foreach($question->options as $option)
                                    <div class="option-item" onclick="selectOption(this)">
                                        <input type="radio" 
                                               name="answers[{{ $question->id }}]" 
                                               id="option_{{ $option->id }}" 
                                               value="{{ $option->id }}"
                                               class="hidden">
                                        <label for="option_{{ $option->id }}" class="option-label">
                                            <span class="option-marker"></span>
                                            <span class="option-text">{{ $option->text }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="quiz-actions mt-8">
                    <a href="{{ route('courses.show', $quiz->course) }}" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i>
                        Back to Course
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i>
                        Submit Quiz
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function selectOption(element) {
        const radio = element.querySelector('input[type="radio"]');
        radio.checked = true;
        
        // Remove selected class from all options in this question
        const questionOptions = element.closest('.question-card').querySelectorAll('.option-item');
        questionOptions.forEach(opt => opt.classList.remove('selected'));
        
        // Add selected class to clicked option
        element.classList.add('selected');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('quiz-form');
        const timeLimit = {{ $quiz->time_limit }};
        let timeRemaining = timeLimit * 60; // Convert to seconds
        const timerDisplay = document.getElementById('time-remaining');

        // Timer functionality
        const timer = setInterval(() => {
            timeRemaining--;
            const minutes = Math.floor(timeRemaining / 60);
            const seconds = timeRemaining % 60;
            timerDisplay.textContent = `Time Remaining: ${minutes}:${seconds.toString().padStart(2, '0')}`;

            if (timeRemaining <= 0) {
                clearInterval(timer);
                form.submit();
            }
        }, 1000);

        // Form submission validation
        form.addEventListener('submit', function(e) {
            const questions = document.querySelectorAll('.question-card');
            let unanswered = 0;

            questions.forEach(question => {
                const selected = question.querySelector('input[type="radio"]:checked');
                if (!selected) {
                    unanswered++;
                }
            });

            if (unanswered > 0) {
                e.preventDefault();
                if (confirm(`You have ${unanswered} unanswered question(s). Are you sure you want to submit?`)) {
                    form.submit();
                }
            }
        });
    });
</script>
@endpush
@endsection 