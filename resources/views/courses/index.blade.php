<!-- resources/views/courses/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Search and Filter Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('courses.index') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search courses..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="category" class="form-select">
                                <option value="">All Categories</option>
                                <option value="Computer Science" {{ request('category') == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                                <option value="Business" {{ request('category') == 'Business' ? 'selected' : '' }}>Business</option>
                                <option value="Design" {{ request('category') == 'Design' ? 'selected' : '' }}>Design</option>
                                <option value="Marketing" {{ request('category') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="skill_level" class="form-select">
                                <option value="">All Skill Levels</option>
                                <option value="Beginner" {{ request('skill_level') == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="Intermediate" {{ request('skill_level') == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="Advanced" {{ request('skill_level') == 'Advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Grid -->
    <div class="row">
        @if($courses->isEmpty())
            <div class="col-12">
                <div class="alert alert-info">
                    No courses found matching your criteria.
                </div>
            </div>
        @else
            @foreach($courses as $course)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $course->title }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($course->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-{{ $course->skill_level === 'Beginner' ? 'success' : ($course->skill_level === 'Intermediate' ? 'warning' : 'danger') }}">
                                    {{ $course->skill_level }}
                                </span>
                                <span class="badge bg-primary">{{ $course->category }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-primary">View Details</a>
                                @if(!$course->isEnrolledBy(Auth::user()))
                                    <form action="{{ route('enroll', $course->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">Enroll Now</button>
                                    </form>
                                @else
                                    <a href="{{ route('courses.show', $course) }}" class="btn btn-success">Continue Learning</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Pagination -->
    <div class="row">
        <div class="col-12">
            {{ $courses->links() }}
        </div>
    </div>
</div>
@endsection
