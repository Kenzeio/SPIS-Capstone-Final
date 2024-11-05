<?php
namespace App\Http\Controllers;

use App\Models\AssignedSports;
use App\Models\Sport;
use App\Models\Student;
use App\Models\StudentPlayer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentPalakasanController extends Controller
{
    public function index()
    {
        $sports = Sport::all();
        $students = Student::all();
        $assignedSports = AssignedSports::with('sport')->get();

        return Inertia::render('CSHCommittee/StudentPlayer', [
            'assignedSports' => $assignedSports,
            'students' => $students,
            'sports' => $sports
        ]);
    }

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'student_assigned_sport_id' => 'required|exists:assigned_sports,id',
        ]);

        try {
            DB::beginTransaction();

            // Log the incoming data
            Log::info('Attempting to create student players with data:', [
                'student_ids' => $request->student_ids,
                'sport_id' => $request->student_assigned_sport_id
            ]);

            // Check for existing entries to avoid duplicates
            foreach ($request->student_ids as $studentId) {
                $exists = StudentPlayer::where('student_id', $studentId)
                    ->where('assigned_sport_id', $request->student_assigned_sport_id)
                    ->exists();
                
                if (!$exists) {
                    $player = StudentPlayer::create([
                        'student_id' => $studentId,
                        'assigned_sport_id' => $request->student_assigned_sport_id,
                    ]);

                    // Log each successful creation
                    Log::info('Created student player:', ['player' => $player]);
                }
            }

            DB::commit();
            
            return redirect()->back()->with('success', 'Students added as players successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the error
            Log::error('Failed to create student players:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to add students as players: ' . $e->getMessage());
        }
    }
}