<?php

namespace App\Http\Controllers;

use App\Models\AssignedSports;
use App\Models\AssignedTeams;
use App\Models\College;
use App\Models\Palakasan;
use App\Models\SportMatch;
use App\Models\SportsVariations;
use App\Models\SportsVariationsMatches;
use App\Models\Venue;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class FreeForAllController extends Controller
{
    public function index(AssignedSports $assignedSports)
    {
        try {
            $colleges =College::all();
            $venues = Venue::all();
            $assignedSports-> load('sport');
            $tournaments = Palakasan::all();
            $teams = AssignedTeams::where('palakasan_id', $assignedSports->palakasan_sport_id)->get();
            $allMatches = SportMatch::all();
            $sportVariations = SportsVariations::where('assigned_sport_id', $assignedSports->id)->get();
            
            return Inertia::render('SSCAdmin/MatchSetup/FreeForAll', [
                'sport' => $assignedSports,
                'tournaments' => $tournaments,
                'teams' => $teams,
                'venues' => $venues,
                'allMatches' => $allMatches,
                'sportVariations' => $sportVariations,
                'colleges' => $colleges
            ]);
        } catch (Exception $e) {
            return redirect()
                ->route('dashboard') // Adjust this route to your fallback route
                ->with('error', 'An error occurred while loading the match setup page. Please try again later.');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'assigned_sport_id' => 'required|exists:assigned_sports,id',
                'sport_variation_name' => 'required|string|max:255',
                'sport_variation_venue_id' => 'required|exists:venues,id',
            ]);
    
            SportsVariations::create($validated);
    
            return redirect()->back()->with('success', 'Sport variation added successfully');
    
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
    
        } catch (QueryException $e) {
            $errorMessage = 'Database error occurred while creating sport variation.';
            
            if ($e->getCode() === '23000') {
                $errorMessage = 'A sport variation with this name already exists.';
            }
    
            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();
    
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'An unexpected error occurred while creating the sport variation. Please try again.')
                ->withInput();
        }
    }
    
    public function generateTeams(Request $request)
    {
        try {
            $validated = $request->validate([
                'sport_variation_id' => 'required|exists:sports_variations,id',
                'teams' => 'required|array',
                'teams.*' => 'exists:assigned_teams,id',
            ]);
    
            DB::beginTransaction();
    
            $sportVariationId = $validated['sport_variation_id'];
    
            // Delete existing teams for this sport variation
            SportsVariationsMatches::where('sport_variation_id', $sportVariationId)->delete();
    
            // Create new entries for each team
            foreach ($validated['teams'] as $teamId) {
                SportsVariationsMatches::create([
                    'sport_variation_id' => $sportVariationId,
                    'sport_variation_team_id' => $teamId,
                    'rank' => null,
                ]);
            }
    
            DB::commit();
    
            return redirect()->back()->with('success', 'Teams generated and saved successfully');
    
        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
    
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Database error occurred while generating teams.')->withInput();
    
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An unexpected error occurred while generating teams.')->withInput();
        }
    }
}