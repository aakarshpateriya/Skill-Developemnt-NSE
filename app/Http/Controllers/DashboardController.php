<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\Note;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $data = [
            'enrolledCourses' => $user->enrolledCourses()->with('instructor')->get(),
            'completedCourses' => $user->completedCourses()->with('instructor')->get(),
            'inProgressCourses' => $user->inProgressCourses()->with('instructor')->get(),
            'recentQuizzes' => Quiz::whereHas('course', function($query) use ($user) {
                $query->whereHas('enrollments', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })->latest()->take(5)->get(),
            'recentNotes' => $user->notes()->latest()->take(5)->get(),
        ];

        if ($user->isInstructor()) {
            $data['instructorCourses'] = Course::where('instructor_id', $user->id)->get();
            $data['instructorQuizzes'] = Quiz::whereHas('course', function($query) use ($user) {
                $query->where('instructor_id', $user->id);
            })->get();
        }

        return view('dashboard', $data);
    }
} 