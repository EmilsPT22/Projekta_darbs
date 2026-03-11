<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use App\Models\InternshipApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InternshipApplicationController extends Controller
{
    /**
     * Show application form for a student
     */
    public function create(Internship $internship)
    {
        // Check if student already applied or is enrolled
        $existingApplication = InternshipApplication::where('internship_id', $internship->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingApplication) {
            return redirect()->route('internships.show', $internship->id)
                ->with('error', 'You have already applied to this internship.');
        }

        if ($internship->students->contains(auth()->id())) {
            return redirect()->route('internships.show', $internship->id)
                ->with('error', 'You are already enrolled in this internship.');
        }

        return view('applications.create', compact('internship'));
    }

    /**
     * Store student application
     */
    public function store(Request $request, Internship $internship)
    {
        $request->validate([
            'cover_letter' => 'nullable|string|max:2000',
            'motivation' => 'required|string|max:2000',
            'phone' => 'nullable|string|max:20',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'public');
        }

        InternshipApplication::create([
            'internship_id' => $internship->id,
            'user_id' => auth()->id(),
            'cover_letter' => $request->cover_letter,
            'motivation' => $request->motivation,
            'phone' => $request->phone,
            'cv_path' => $cvPath,
            'status' => 'pending',
        ]);

        return redirect()->route('internships.show', $internship->id)
            ->with('success', 'Your application has been submitted successfully!');
    }

    /**
     * Show all applications for an internship (manager only)
     */
    public function index(Internship $internship)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'internship_manager'])) {
            abort(403);
        }

        $applications = InternshipApplication::with('student')
            ->where('internship_id', $internship->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('applications.index', compact('internship', 'applications'));
    }

    /**
     * Show single application details (manager only)
     */
    public function show(Internship $internship, InternshipApplication $application)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'internship_manager'])) {
            abort(403);
        }

        return view('applications.show', compact('internship', 'application'));
    }

    /**
     * Approve application (manager only)
     */
    public function approve(Internship $internship, InternshipApplication $application)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'internship_manager'])) {
            abort(403);
        }

        $application->update([
            'status' => 'approved',
            'manager_comment' => request('manager_comment'),
        ]);

        // Add student to internship
        $internship->students()->attach($application->user_id);

        // Assign themes to student
        $themes = $internship->themes;
        foreach ($themes as $theme) {
            $theme->users()->attach($application->user_id, [
                'assigned_hours' => $theme->max_hours,
                'used_hours' => 0,
            ]);
        }

        return redirect()->route('applications.index', $internship->id)
            ->with('success', 'Application approved. Student added to internship.');
    }

    /**
     * Reject application (manager only)
     */
    public function reject(Internship $internship, InternshipApplication $application)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'internship_manager'])) {
            abort(403);
        }

        $application->update([
            'status' => 'rejected',
            'manager_comment' => request('manager_comment'),
        ]);

        return redirect()->route('applications.index', $internship->id)
            ->with('success', 'Application rejected.');
    }
}
