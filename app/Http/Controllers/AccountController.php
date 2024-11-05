<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentAccount;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::all();
        $studentAccounts = StudentAccount::with('student')->get();
        return Inertia::render('SSCAdmin/Account',[
            'students' => $students,
            'studentAccounts' => $studentAccounts,

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id', // Ensure the student exists in the students table
            'role' => 'required|string|max:255',
            'status' => 'required|boolean', // Assuming 'status' is either true or false
        ]);
    
        // Create the student account
        StudentAccount::create([
            'student_id' => $validated['student_id'],
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);
    
        // Redirect back with success message
        return redirect()->back()->with('success', 'Account created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
