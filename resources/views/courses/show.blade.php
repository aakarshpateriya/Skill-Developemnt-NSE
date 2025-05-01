@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Course Details -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h1 class="card-title mb-4">{{ $course->title }}</h1>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <span class="badge bg-{{ $course->skill_level === 'Beginner' ? 'success' : ($course->skill_level === 'Intermediate' ? 'warning' : 'danger') }} me-2">
                                {{ $course->skill_level }}
                            </span>
                            <span class="badge bg-primary">{{ $course->category }}</span>
                        </div>
                        <div>
                            <span class="text-muted">
                                <i class="fas fa-users me-1"></i>
                                {{ $course->enrollments->count() }} Students
                            </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5>Description</h5>
                        <p class="text-muted">{{ $course->description }}</p>
                    </div>

                    @if($course->isEnrolledBy(Auth::user()))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            You are enrolled in this course
                        </div>
                    @endif
                </div>
            </div>

            <!-- Course Content -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Course Content</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Introduction</h6>
                                <small class="text-muted">15 minutes</small>
                            </div>
                            <p class="mb-1 text-muted">Get started with the basics of the course</p>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Main Concepts</h6>
                                <small class="text-muted">45 minutes</small>
                            </div>
                            <p class="mb-1 text-muted">Learn the core concepts and principles</p>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Practical Exercises</h6>
                                <small class="text-muted">30 minutes</small>
                            </div>
                            <p class="mb-1 text-muted">Apply what you've learned through hands-on exercises</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Enrollment Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    @if(!$course->isEnrolledBy(Auth::user()))
                        <form action="{{ route('enroll', $course->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100 mb-3">Enroll Now</button>
                        </form>
                    @else
                        <a href="#" class="btn btn-success w-100 mb-3">Continue Learning</a>
                    @endif

                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Duration</span>
                        <span>2 hours</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Level</span>
                        <span>{{ $course->skill_level }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Category</span>
                        <span>{{ $course->category }}</span>
                    </div>
                </div>
            </div>

            <!-- Instructor Card -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Instructor</h5>
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://ui-avatars.com/api/?name=John+Doe&background=random" class="rounded-circle me-3" width="50" height="50">
                        <div>
                            <h6 class="mb-0">NSE Education</h6>
                            <small class="text-muted">Senior Instructor</small>
                        </div>
                    </div>
                    <p class="text-muted">Experienced instructor with over 10 years of teaching experience in this field.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 