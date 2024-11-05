<?php

namespace App\Http\Controllers;

use App\Models\Sport;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sports = Sport::all(); // Fetch from the database
        return Inertia::render('SSCAdmin/Sport', [
            'sports' => $sports, // Pass to the view
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
            'description' => 'required|string|max:2500'
        
        ]);
                    
        // Create a new college using the validated data
        Sport::create($validated);
                    
        // Redirect with a success message
        return redirect()->route('sport.index')->with('success', 'College successfully added.');
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
        $sports = Sport::findOrFail($id); // Find the product by ID

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',

        ]);

        $sports->update($request->only('name', 'description')); // Update the product

        return redirect()->route('sport.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sport $sport)
    {
        $sport->delete();
    
        return redirect()->route('sport.index')->with('success', 'Product deleted successfully.');
    }
}
