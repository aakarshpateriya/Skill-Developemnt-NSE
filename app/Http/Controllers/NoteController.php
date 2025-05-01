<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Course;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function courseNotes(Course $course)
    {
        $notes = $course->notes()
            ->where('user_id', auth()->id())
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('notes.course', compact('course', 'notes'));
    }

    public function index()
    {
        $notes = auth()->user()->notes()
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('notes.index', compact('notes'));
    }

    public function create()
    {
        $courses = auth()->user()->enrolledCourses;
        return view('notes.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'color' => 'nullable|string|max:7'
        ]);

        $note = auth()->user()->notes()->create($validated);

        return redirect()->route('notes.course', $note->course)
            ->with('success', 'Note created successfully!');
    }

    public function show(Note $note)
    {
        $this->authorize('view', $note);
        return view('notes.show', compact('note'));
    }

    public function edit(Note $note)
    {
        $this->authorize('update', $note);
        $courses = auth()->user()->enrolledCourses;
        return view('notes.edit', compact('note', 'courses'));
    }

    public function update(Request $request, Note $note)
    {
        $this->authorize('update', $note);

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'color' => 'nullable|string|max:7'
        ]);

        $note->update($validated);

        return redirect()->route('notes.course', $note->course)
            ->with('success', 'Note updated successfully!');
    }

    public function destroy(Note $note)
    {
        $this->authorize('delete', $note);
        $note->delete();

        return redirect()->route('notes.course', $note->course)
            ->with('success', 'Note deleted successfully!');
    }

    public function pin(Note $note)
    {
        $this->authorize('update', $note);
        $note->update(['is_pinned' => true]);

        return back()->with('success', 'Note pinned successfully!');
    }

    public function unpin(Note $note)
    {
        $this->authorize('update', $note);
        $note->update(['is_pinned' => false]);

        return back()->with('success', 'Note unpinned successfully!');
    }
}
