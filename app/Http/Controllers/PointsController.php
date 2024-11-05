<?php

namespace App\Http\Controllers;

use App\Models\Points;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PointsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $points = Points::all(); // Fetch from the database
        return Inertia::render('SSCAdmin/Points', [
            'points' => $points, // Pass to the view
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
        // Validate the input fields in a single call
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'pointdescription' => 'required|string|max:255',
            'rankdescription' => 'required|string|max:255',
        ]);
                    
        // Create a new college using the validated data
        Points::create($validated);
                    
        // Redirect with a success message
        return redirect()->route('points.index')->with('success', 'Points successfully added.');
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
        $points = Points::findOrFail($id); // Find the product by ID

        $request->validate([
            'name' => 'required|string|max:255',
            'pointdescription' => 'required|string|max:255',
            'rankdescription' => 'required|string|max:255',

        ]);

        $points->update($request->only('name', 'pointdescription','rankdescription',)); // Update the product

        return redirect()->route('points.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Points $points)
    {
        $points->delete();
    
        return redirect()->route('points.index')->with('success', 'Points deleted successfully.');
    }
}
