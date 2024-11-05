<?php

namespace App\Http\Controllers;

use App\Models\AssignedTeams;
use App\Models\College;
use App\Models\Palakasan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PalakasanTeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $palakasans = Palakasan::all();
        $colleges = College::all();
        $assignedTeams = AssignedTeams::with('college')->get(); // Eager load college

        // Get the latest Palakasan
        $latestPalakasan = Palakasan::latest()->first();
        return Inertia::render('SSCAdmin/Palakasan/Teams', [
            'colleges' => $colleges,
            'palakasans' => $palakasans,
            'assignedTeams' => $assignedTeams,
            'latestPalakasan' => $latestPalakasan, // Pass latest Palakasan to the view
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
        // Validate incoming request
        $validated = $request->validate([
            'assigned_team_name' => 'required|string|max:255',
            'college_id' => 'required|exists:colleges,id',
            'palakasan_id' => 'required|exists:palakasans,id', // Validate palakasan_id
        ], [
            'assigned_team_name.required' => 'The team name is required.',
            'college_id.exists' => 'The selected college does not exist.',
            'palakasan_id.exists' => 'The selected Palakasan does not exist.', // Custom message for Palakasan
        ]);

        // Create a new assigned team
        AssignedTeams::create($validated);

        // Return a success response or redirect
        return redirect('/palakasan/team')->with('success', 'Team created successfully!');
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
