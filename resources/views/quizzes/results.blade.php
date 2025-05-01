@extends('layouts.app')

@section('content')
<div class="container py-8">
    <div class="max-w-4xl mx-auto">
        <div class="quiz-container">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold mb-4">Quiz Results</h1>
                <div class="text-2xl font-semibold text-primary">
                    Score: {{ $attempt->score }}%
                </div>
            </div>

            <div class="space-y-8">
                @foreach($attempt->answers as $answer)
                    <div class="quiz-question">
                        <h3 class="text-lg font-semibold mb-4">
                            {{ $loop->iteration }}. {{ $answer->question->text }}
                        </h3>
                        
                        <div class="quiz-options">
                            @foreach($answer->question->options as $option)
                                <div class="quiz-option {{ $option->id === $answer->selected_option_id ? 'selected' : '' }} 
                                                     {{ $option->is_correct ? 'correct' : '' }}">
                                    <span class="option-text">{{ $option->text }}</span>
                                    @if($option->id === $answer->selected_option_id)
                                        <span class="ml-2">
                                            @if($option->is_correct)
                                                <i class="fas fa-check text-success"></i>
                                            @else
                                                <i class="fas fa-times text-danger"></i>
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('quizzes.index') }}" class="btn btn-primary">
                    Back to Quizzes
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 