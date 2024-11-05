<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Inertia\Inertia;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::all(); // Fetch from the database
        return Inertia::render('SSCAdmin/Student', [
            'students' => $students, // Pass to the view
        ]);
    }

    public function import(Request $request)
    {
        // Validate the file input
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Load the uploaded Excel file
        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());

        // Get the first worksheet
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        $updatedCount = 0;
        $createdCount = 0;

        // Skip the first row if it contains headers
        foreach ($rows as $index => $row) {
            if ($index === 0) continue; // Skip header row

            // Check if all required fields are present
            if (!empty($row[0]) && !empty($row[1]) && !empty($row[2])) {
                // Use updateOrCreate to update existing records or create new ones
                $student = Student::updateOrCreate(
                    ['first_name' => $row[0]], // Check if a student with this name exists
                    [
                        'last_name' => $row[1],
                        'id_number' => $row[2],
                        'univ_email' => $row[3],
                        'college' => $row[4],
                        'contact' => $row[5],
                    ]
                );

                if ($student->wasRecentlyCreated) {
                    $createdCount++;
                } else {
                    $updatedCount++;
                }
            }
        }

        return redirect()->route('student.index')->with('success', "Import completed. Created: $createdCount, Updated: $updatedCount");
    }
}