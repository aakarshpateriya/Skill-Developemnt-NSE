<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function enroll($courseId)
    {
        $course = Course::findOrFail($courseId);
        
        // Check if user is already enrolled
        if ($course->isEnrolledBy(auth()->user())) {
            return back()->with('error', 'You are already enrolled in this course.');
        }

        try {
            Enrollment::create([
                'user_id' => auth()->id(),
                'course_id' => $courseId,
                'status' => 'in_progress',
                'progress' => 0
            ]);

            return redirect()->route('courses.show', $course)
                ->with('success', 'Successfully enrolled in the course!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error enrolling in course: ' . $e->getMessage());
        }
    }

    public function updateProgress(Request $request, $enrollmentId)
    {
        $enrollment = Enrollment::findOrFail($enrollmentId);
        
        // Ensure the enrollment belongs to the authenticated user
        if ($enrollment->user_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        try {
            $enrollment->update([
                'progress' => $request->progress,
                'status' => $request->progress >= 100 ? 'completed' : 'in_progress'
            ]);

            return back()->with('success', 'Progress updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating progress: ' . $e->getMessage());
        }
    }
}
