<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use App\Models\Theme;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    private function authorizeAdmin()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'internship_manager'])) {
            abort(403);
        }
    }

    private function totalAllowedHours($months)
    {
        return $months * 160;
    }

    private function currentUsedHours(Internship $internship)
    {
        return $internship->themes->sum('max_hours');
    }

    public function index(Internship $internship)
    {
        // Admin, internship manager, and teacher can view themes
        if (!auth()->user()->hasAnyRole(['admin', 'internship_manager', 'teacher'])) {
            abort(403);
        }

        return view('themes.index', [
            'internship' => $internship,
            'themes' => $internship->themes
        ]);
    }

    public function create(Internship $internship)
    {
        $this->authorizeAdmin();

        return view('themes.create', [
            'internship' => $internship
        ]);
    }

    public function store(Request $request, Internship $internship)
    {
        $this->authorizeAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'max_hours' => 'required|integer|min:1',
            'description' => 'nullable|string'
        ]);

        $allowed = $this->totalAllowedHours($internship->length);
        $current = $this->currentUsedHours($internship);
        $requested = $request->max_hours;

        if ($current + $requested > $allowed) {
            return back()->withErrors([
                'max_hours' => 'Total hours cannot exceed ' . $allowed . ' for this internship. Currently used: ' . $current . '.'
            ])->withInput();
        }

        $internship->themes()->create([
            'name' => $request->name,
            'max_hours' => $request->max_hours,
            'description' => $request->description
        ]);

        return redirect()->route('themes.index', $internship->id);
    }

    public function edit(Internship $internship, Theme $theme)
    {
        $this->authorizeAdmin();

        return view('themes.edit', [
            'internship' => $internship,
            'theme' => $theme
        ]);
    }

    public function update(Request $request, Internship $internship, Theme $theme)
    {
        $this->authorizeAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'max_hours' => 'required|integer|min:1',
            'description' => 'nullable|string'
        ]);

        $allowed = $this->totalAllowedHours($internship->length);
        $current = $this->currentUsedHours($internship) - $theme->max_hours;
        $requested = $request->max_hours;

        if ($current + $requested > $allowed) {
            return back()->withErrors([
                'max_hours' => 'Total hours cannot exceed ' . $allowed . ' for this internship. Currently used: ' . $current . '.'
            ])->withInput();
        }

        $theme->update([
            'name' => $request->name,
            'max_hours' => $request->max_hours,
            'description' => $request->description
        ]);

        return redirect()->route('themes.index', $internship->id);
    }

    public function destroy(Internship $internship, Theme $theme)
    {
        $this->authorizeAdmin();

        $theme->delete();

        return redirect()->route('themes.index', $internship->id);
    }
}
