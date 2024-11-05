<?php

namespace App\Http\Controllers;

use App\Models\College;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CollegeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colleges = College::all(); // Fetch from the database
        return Inertia::render('SSCAdmin/College', [
            'colleges' => $colleges, // Pass to the view
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
            'shortName' => 'required|string|max:255',
            'teamName' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
    
        // Create a new college using the validated data
        College::create($validated);
    
        // Redirect with a success message
        return redirect()->route('college.index')->with('success', 'College successfully added.');
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
        $colleges = College::findOrFail($id); // Find the product by ID

        $request->validate([
            'name' => 'required|string|max:255',
            'shortName' => 'required|string|max:255',
            'teamName' => 'required|string|max:255',
            'description' => 'nullable|string',


        ]);

        $colleges->update($request->only('name', 'shortName', 'teamName', 'description')); // Update the product

        return redirect()->route('college.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(College $college)
    {
        $college->delete();
    
        return redirect()->route('college.index')->with('success', 'Product deleted successfully.');
    }
}
