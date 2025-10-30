<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use Illuminate\Http\Request;
use App\Models\User;

class InternshipController extends Controller
{
    public function index()
    {
        $internships = Internship::all();
        return view('internships.index', ['internships' => $internships]);
    }


    public function create()
    {
        return view('internships.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'length' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Internship::create($request->all());

        return redirect()->route('internships.index')->with('success', 'Internship created successfully');
    }

    public function show(Internship $internship)
    {
        $users = User::all();

        return view('internships.show', ['internship' => $internship, 'users' => $users]);
    }

    public function edit(Internship $internship)
    {
        return view('internships.edit', ['internship' => $internship]);
    }

    public function update(Request $request, Internship $internship)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'length' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $internship->update($request->all());

        return redirect()->route('internships.index')->with('success', 'Internship updated successfully');
    }

    public function destroy(Internship $internship)
    {
        $internship->delete();

        return redirect()->route('internships.index')->with('success', 'Internship deleted successfully');
    }
}
