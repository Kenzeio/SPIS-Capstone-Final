<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CollegeController;
use App\Http\Controllers\DoubleEliminationController;
use App\Http\Controllers\FreeForAllController;
use App\Http\Controllers\OnePalakasanController;
use App\Http\Controllers\PalakasanController;
use App\Http\Controllers\PalakasanSportsController;
use App\Http\Controllers\PalakasanTeamController;
use App\Http\Controllers\PointsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SingleEliminationController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentPalakasanController;
use App\Http\Controllers\VenueController;
use App\Models\AssignedSports;

Route::resource('product', ProductController::class);
Route::resource('college', CollegeController::class);
Route::resource('venue', VenueController::class);
Route::resource('sport', SportController::class);
Route::resource('points', PointsController::class);
Route::resource('account', AccountController::class);
Route::resource('palakasan/details',PalakasanController::class);
Route::post('/palakasan/details', [PalakasanController::class, 'store'])->name('palakasan.store');
Route::resource('palakasan/team', PalakasanTeamController::class);
Route::resource('palakasan/sportselection', PalakasanSportsController::class);
Route::resource('studentplayer', StudentPalakasanController::class);
Route::post('studentplayer/store', [StudentPalakasanController::class, 'store'])->name('player.store');

Route::get('details/sportview/{sport}', function ($sport) {
    $sportModel = AssignedSports::findOrFail($sport);

    switch ($sportModel->setup) {
        case 'Single Elimination':
            return app(SingleEliminationController::class)->index($sportModel);
        case 'Double Elimination':
            return app(DoubleEliminationController::class)->index($sportModel);
        case 'Free for All':
            return app(FreeForAllController::class)->index($sportModel);
        default:
            abort(404, 'Invalid sport setup');
    }
})->name('sportview.index');

Route::post('/matches', [SingleEliminationController::class, 'store_single_elimination'])->name('matchesSingle.store');
Route::post('/matchesDE', [DoubleEliminationController::class, 'store'])->name('matches.store');
Route::post('/sport-variations', [FreeForAllController::class, 'store'])->name('sport-variations.store');
Route::post('/generate-teams', [FreeForAllController::class, 'generateTeams'])->name('generate-teams');
Route::post('/matches/update-date-time', [SingleEliminationController::class, 'updateDateTime'])->name('matches.updateDateTime');
Route::post('/resultsSE', [SingleEliminationController::class, 'storeResult'])->name('results.store');
Route::post('/resultsDE', [DoubleEliminationController::class, 'storeResult'])->name('results.store');



//trial routinf
Route::get('details', [OnePalakasanController::class, 'details'])->name('palakasan.details'); // Displays the details
Route::post('detail/store', [OnePalakasanController::class, 'store_palakasan'])->name('palakasan.store');
Route::post('sport/store', [OnePalakasanController::class, 'store_sports'])->name('palakasanSport.store');
Route::post('team/store', [OnePalakasanController::class, 'store_teams'])->name('team.store');
Route::put('/palakasan/{id}/update-status', [OnePalakasanController::class, 'updateStatus'])->name('palakasan.updateStatus');
Route::get('/selectedSport/{id}', [OnePalakasanController::class, 'showSport'])->name('sport.show');
Route::post('/match-result', [OnePalakasanController::class, 'storeMatchResult'])->name('match.result.store');


Route::resource('student', StudentController::class);
Route::post('student/import', [StudentController::class, 'import'])->name('student.import');

Route::get('/', function () {
    return Inertia::render('SSCAdmin/Dashboard');
});






