<?php

namespace App\Http\Controllers;

use App\Models\AssignedSports;
use App\Models\Palakasan;
use App\Models\Sport;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PalakasanSportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $palakasans = Palakasan::all(); // Ensure this returns data
        $sports = Sport::all();
        $assignedSports = AssignedSports::with('sport')->get();
        $latestPalakasan = Palakasan::latest()->first();
    
        return Inertia::render('SSCAdmin/Palakasan/SelectedSport', [
            'palakasans' => $palakasans,
            'sports' => $sports,
            'assignedSports' => $assignedSports,
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
        // Validate the request
        $validated = $request->validate([
            'palakasan_sport_id' => 'required|exists:palakasans,id', // Added palakasan_sport_id validation
            'sport_id' => 'required|exists:sports,id',
            'categories' => 'required|string|in:Men,Women,Mixed',
            'setup' => 'required|string|in:Double Elimination,Single Elimination,Free for All',
        ]);
    
        // Store the validated data in the database
        AssignedSports::create([
            'palakasan_sport_id' => $validated['palakasan_sport_id'], // Ensure this matches the form field name
            'sport_id' => $validated['sport_id'],
            'categories' => $validated['categories'],
            'setup' => $validated['setup'],
        ]);
    
        return redirect()->back()->with('success', 'Team assigned to the sport successfully.');
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
