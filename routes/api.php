<?php

use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\ExamentionController;
use App\Http\Controllers\Api\ExamResultController;
use App\Http\Controllers\Api\FeeController;
use App\Http\Controllers\Api\LibraryController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\SchoolClassController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    Route::delete('/logout', [AuthController::class, 'logout']);
    Route::get('/profile/info', [AuthController::class, 'profile_info']);
});

// Auth::routes(['verify' => true]);
Route::controller(SchoolClassController::class)
->prefix('class')
->group(function () {
    Route::get('/index', 'index');
    Route::get('/showItem/{id}', 'show')->name('Class.show');
    Route::get('/showItem/{id}/users', 'show')->name('Class.show');
    Route::post('/storeItem', 'store');
    Route::post('/updateItem/{id}', 'update');
    Route::delete('/deleteItem/{id}', 'destroy');
});

Route::controller(SectionController::class)
->prefix('section')
->group(function () {
    Route::get('/index', 'index');
    Route::get('/showItem/{section}', 'show');
    Route::post('/storeItem', 'store');
    Route::post('/updateItem/{section}', 'update');
    Route::delete('/deleteItem/{section}', 'destroy');
});


Route::controller(SubjectController::class)
->prefix('subject')
->group(function () {
    Route::get('/allSubjects', [SubjectController::class, 'index']);
});


Route::get('/allSchedules', [ScheduleController::class, 'index']);
Route::get('/allAssignments', [AssignmentController::class, 'index']);
Route::get('/allAttendances', [AttendanceController::class, 'index']);
Route::get('/allDocuments', [DocumentController::class, 'index']);
Route::get('/allExamentions', [ExamentionController::class, 'index']);
Route::get('/allExamResults', [ExamResultController::class, 'index']);
Route::get('/allFees', [FeeController::class, 'index']);
