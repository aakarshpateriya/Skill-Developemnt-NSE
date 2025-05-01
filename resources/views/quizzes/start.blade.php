@extends('layouts.app')

@section('content')
<div class="container py-8">
    <div class="max-w-4xl mx-auto">
        <div class="quiz-container">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">{{ $quiz->title }}</h1>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Time Remaining</div>
                    <div id="timer" class="text-xl font-bold text-primary">{{ $quiz->time_limit }}:00</div>
                </div>
            </div>

            <form action="{{ route('quizzes.submit', $quiz) }}" method="POST" id="quizForm">
                @csrf
                <div class="space-y-8">
                    @foreach($quiz->questions as $index => $question)
                        <div class="quiz-question">
                            <h3 class="text-lg font-semibold mb-4">
                                {{ $index + 1 }}. {{ $question->text }}
                            </h3>
                            
                            <div class="quiz-options">
                                @foreach($question->options as $option)
                                    <label class="quiz-option">
                                        <input type="radio" 
                                               name="answers[{{ $question->id }}]" 
                                               value="{{ $option->id }}"
                                               class="hidden">
                                        <span class="option-text">{{ $option->text }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 text-center">
                    <button type="submit" class="btn btn-primary">
                        Submit Quiz
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Timer functionality
    let timeLeft = {{ $quiz->time_limit * 60 }};
    const timerElement = document.getElementById('timer');
    
    const timer = setInterval(() => {
        timeLeft--;
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        
        if (timeLeft <= 0) {
            clearInterval(timer);
            document.getElementById('quizForm').submit();
        }
    }, 1000);

    // Quiz option selection
    document.querySelectorAll('.quiz-option').forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Remove selected class from all options in this question
            const questionOptions = this.closest('.quiz-question').querySelectorAll('.quiz-option');
            questionOptions.forEach(opt => opt.classList.remove('selected'));
            
            // Add selected class to clicked option
            this.classList.add('selected');
        });
    });
</script>
@endpush
@endsection 