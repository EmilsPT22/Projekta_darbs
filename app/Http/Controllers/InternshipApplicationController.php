<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use App\Models\InternshipApplication;
use App\Notifications\NewApplicationNotification;
use App\Notifications\ApplicationStatusChangedNotification;
use Illuminate\Http\Request;

class InternshipApplicationController extends Controller
{
    /**
     * Show application form for a student
     */
    public function create(Internship $internship)
    {
        $user = auth()->user();

        // Check if student is already enrolled in any internship
        $existingInternship = $user->internships()->first();
        if ($existingInternship) {
            return redirect()->route('internships.show', $internship->id)
                ->with('error', 'You are already enrolled in an internship: ' . $existingInternship->name);
        }

        // Check if student already applied to this internship
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
        $user = auth()->user();

        // Check if student is already enrolled in any internship
        $existingInternship = $user->internships()->first();
        if ($existingInternship) {
            return redirect()->route('internships.show', $internship->id)
                ->with('error', 'You are already enrolled in an internship: ' . $existingInternship->name);
        }

        // Check if student already applied to this internship
        $existingApplication = InternshipApplication::where('internship_id', $internship->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingApplication) {
            return redirect()->route('internships.show', $internship->id)
                ->with('error', 'You have already applied to this internship.');
        }

        // Check if student is already enrolled in this internship
        if ($internship->students->contains(auth()->id())) {
            return redirect()->route('internships.show', $internship->id)
                ->with('error', 'You are already enrolled in this internship.');
        }

        $request->validate([
            'cover_letter' => 'nullable|string|max:2000',
            'motivation' => 'required|string|max:2000',
            'phone' => 'nullable|string|max:20',
        ]);

        $application = InternshipApplication::create([
            'internship_id' => $internship->id,
            'user_id' => auth()->id(),
            'cover_letter' => $request->cover_letter,
            'motivation' => $request->motivation,
            'phone' => $request->phone,
            'status' => 'pending',
        ]);

        // Notify internship manager about new application
        $internshipManager = \App\Models\User::role('internship_manager')->first();
        if ($internshipManager) {
            $internshipManager->notify(new NewApplicationNotification($application));
        }

        // Also notify all admins
        $admins = \App\Models\User::role('admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewApplicationNotification($application));
        }

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

        // Check if student is already enrolled in another internship
        $existingInternship = $application->student->internships()->first();
        if ($existingInternship && $existingInternship->id !== $internship->id) {
            return redirect()->route('applications.index', $internship->id)
                ->with('error', 'Student is already enrolled in another internship: ' . $existingInternship->name);
        }

        $oldStatus = $application->status;
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

        // Notify student about approval
        $application->student->notify(
            new ApplicationStatusChangedNotification($application, $oldStatus, 'approved')
        );

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

        $oldStatus = $application->status;
        $application->update([
            'status' => 'rejected',
            'manager_comment' => request('manager_comment'),
        ]);

        // Notify student about rejection
        $application->student->notify(
            new ApplicationStatusChangedNotification($application, $oldStatus, 'rejected')
        );

        return redirect()->route('applications.index', $internship->id)
            ->with('success', 'Application rejected.');
    }

    /**
     * Show student their own application
     */
    public function studentView(Internship $internship)
    {
        $application = InternshipApplication::where('internship_id', $internship->id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$application) {
            return redirect()->route('internships.show', $internship->id)
                ->with('error', 'You have not applied to this internship.');
        }

        return view('applications.student-view', compact('internship', 'application'));
    }
}
