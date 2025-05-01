<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'instructor_id',
        'is_published'
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function enrolledUsers()
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot('status', 'progress')
            ->withTimestamps();
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function isEnrolledBy(User $user)
    {
        return $this->enrollments()->where('user_id', $user->id)->exists();
    }

    public function getEnrollmentForUser(User $user)
    {
        return $this->enrollments()->where('user_id', $user->id)->first();
    }

    public function getPublishedQuizzesAttribute()
    {
        return $this->quizzes()->where('is_published', true)->get();
    }
}

