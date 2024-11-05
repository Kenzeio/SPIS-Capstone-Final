<?php

namespace App\Http\Controllers;

use App\Models\AssignedSports;
use App\Models\AssignedTeams;
use App\Models\MatchResult;
use App\Models\Palakasan;
use App\Models\SportMatch;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class SingleEliminationController extends Controller
{
    public function index(AssignedSports $assignedSports)
    {
        $venues = Venue::all();
        $assignedSports->load('sport');
        $tournaments = Palakasan::all();
        $teams = AssignedTeams::where('palakasan_id', $assignedSports->palakasan_sport_id)->get();
        $matches = SportMatch::where('assigned_sport_id', $assignedSports->id)->get();
        $results = MatchResult::whereIn('sport_match_id', $matches->pluck('id'))->get();
        $allMatches = SportMatch::all();
        return Inertia::render('SSCAdmin/MatchSetup/SingleElimination', [
            'sport' => $assignedSports,
            'tournaments' => $tournaments,
            'teams' => $teams,
            'matches' => $matches,
            'results' => $results,
            'venues' => $venues,
            'allMatches' => $allMatches,
        ]);
    }   

    public function store_single_elimination(Request $request)
    {
        try {
            $matches = [];
            $matchesData = $request->all();
    
            DB::beginTransaction();
    
            foreach ($matchesData as $match) {
                // Ensure match_venue_id is present
                if (!isset($match['match_venue_id'])) {
                    throw new \InvalidArgumentException('Venue ID is required for each match');
                }
    
                if (is_null($match['teamA_id']) || is_null($match['teamB_id'])) {
                    $validatedMatch = $this->validateFutureMatch($match);
                } else {
                    $validatedMatch = $this->validateMatch($match);
                }
    
                $existingMatch = SportMatch::where([
                    ['assigned_sport_id', $match['assigned_sport_id']],
                    ['round', $match['round']],
                    ['game', $match['game']],
                ])->first();
    
                if (!$existingMatch) {
                    $createdMatch = SportMatch::create([
                        'assigned_sport_id' => $match['assigned_sport_id'],
                        'teamA_id' => $match['teamA_id'],
                        'teamB_id' => $match['teamB_id'],
                        'round' => $match['round'],
                        'game' => $match['game'],
                        'bracket_type' => $match['bracket_type'],
                        'elimination_type' => $match['elimination_type'],
                        'status' => $match['status'] ?? 'pending',
                        'match_venue_id' => $match['match_venue_id'], // Ensure this is included
                        'date' => $match['date'] ?? null,
                        'time' => $match['time'] ?? null
                    ]);
    
                    $matches[] = $createdMatch;
                }
            }
    
            DB::commit();
    
            return response()->json([
                'message' => 'Matches created successfully',
                'matches' => $matches
            ], 200);
    
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'error' => 'Failed to create matches: ' . $e->getMessage()
            ], 500);
        }
    }

    protected function validateMatch($match)
    {
        if (!is_array($match)) {
            throw new \InvalidArgumentException('Match data must be an array');
        }

        $rules = [
            'assigned_sport_id' => 'required|exists:assigned_sports,id',
            'teamA_id' => 'required|exists:assigned_teams,id',
            'teamB_id' => 'required|exists:assigned_teams,id|different:teamA_id',
            'round' => 'required|integer|min:1',
            'game' => 'required|string',
            'bracket_type' => 'required|string',
            'elimination_type' => 'required|string',
            'status' => 'string|in:pending,completed',
            'match_venue_id' => 'required|exists:venues,id',
            'date' => 'nullable|date_format:Y-m-d',
            'time' => 'nullable'
        ];

        return validator($match, $rules)->validate();
    }

    protected function validateFutureMatch($match)
    {
        if (!is_array($match)) {
            throw new \InvalidArgumentException('Match data must be an array');
        }

        $rules = [
            'assigned_sport_id' => 'required|exists:assigned_sports,id',
            'teamA_id' => 'nullable',
            'teamB_id' => 'nullable',
            'round' => 'required|integer|min:1',
            'game' => 'required|string',
            'bracket_type' => 'required|string',
            'elimination_type' => 'required|string',
            'status' => 'string|in:pending,completed',
            'match_venue_id' => 'required|exists:venues,id',
            'date' => 'nullable|date_format:Y-m-d',
            'time' => 'nullable'
        ];

        return validator($match, $rules)->validate();
    }

    public function storeResult(Request $request)
    {
        $request->validate([
            'sport_match_id' => 'required|exists:sport_matches,id',
            'teamA_score' => 'required|integer|min:0',
            'teamB_score' => 'required|integer|min:0',
            'winning_team_id' => 'required|exists:assigned_teams,id',
            'losing_team_id' => 'required|exists:assigned_teams,id'
        ]);

        try {
            $existingResult = MatchResult::where('sport_match_id', $request->sport_match_id)->first();

            if ($existingResult) {
                $existingResult->update($request->all());
                $result = $existingResult;
            } else {
                $result = MatchResult::create($request->all());
            }

            $match = SportMatch::find($request->sport_match_id);
            $match->update(['status' => 'completed']);

            $nextRound = $match->round + 1;

            $nextRoundMatch = SportMatch::where([
                ['assigned_sport_id', $match->assigned_sport_id],
                ['round', $nextRound],
                ['status', 'pending']
            ])->where(function($query) {
                $query->whereNull('teamA_id')
                      ->orWhereNull('teamB_id');
            })->first();

            if ($nextRoundMatch) {
                if (is_null($nextRoundMatch->teamA_id)) {
                    $nextRoundMatch->update(['teamA_id' => $request->winning_team_id]);
                } elseif (is_null($nextRoundMatch->teamB_id)) {
                    $nextRoundMatch->update(['teamB_id' => $request->winning_team_id]);
                }
            }

            return redirect()->back()->with('message', 'Score saved successfully');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to save score: ' . $e->getMessage()]);
        }
    }

    public function updateDateTime(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'matchId' => 'required|exists:sport_matches,id',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $match = SportMatch::findOrFail($request->matchId);
            
            // Update the match with the provided date, time, and venue
            $match->update([
                'date' => $request->date,
                'time' => $request->time,
            ]);

            DB::commit();

            return redirect()->back()->with('message', 'Match schedule and venue updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update match schedule and venue: ' . $e->getMessage()
            ], 500);
        }
    }
}