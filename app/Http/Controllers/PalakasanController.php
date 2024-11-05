<?php

namespace App\Http\Controllers;

use App\Models\AssignedSports;
use App\Models\AssignedTeams;
use App\Models\Palakasan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PalakasanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assignedSports = AssignedSports::all();
        $assignedTeams = AssignedTeams::all();
        $palakasans = Palakasan::all();
        // Get the latest Palakasan
        $latestPalakasan = Palakasan::latest()->first();
        return Inertia::render('SSCAdmin/Palakasan/Details',[
            'palakasans' => $palakasans,
            'assignedSports' => $assignedSports,
            'assignedTeams' => $assignedTeams,
            'latestPalakasan' => $latestPalakasan, // Pass latest Palakasan to the view
        ]);
    }

    public function anotherView()
    {
        $palakasans = Palakasan::all(); // Fetch all Palakasan entries
    
        return Inertia::render('SSCAdmin/Palakasan/PalakasanLayout', [
            'palakasans' => $palakasans, // Pass palakasans to the view
        ]);
    }
    
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'theme' => 'required|string|max:255',
            'tagline' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|boolean',
        ]);
        
                // Create a new Palakasan record
        Palakasan::create([
            'year' => date('Y'), // Set the year to the current year
            'theme' => $validated['theme'],
            'tagline' => $validated['tagline'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => $validated['status'],
        ]);
        
                // Redirect or return a response
        return redirect('/palakasan/details')->with('success', 'Palakasan created successfully.');
           
    }

    public function destroy(string $id)
    {
        //
    }
}
