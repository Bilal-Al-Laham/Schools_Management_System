<?php

use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\ExamentionController;
use App\Http\Controllers\Api\ExamResultController;
use App\Http\Controllers\Api\FeeController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\SchoolClassController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\Auth\AuthController;
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
Route::post('/adminLogin', [AuthController::class, 'Adminlogin']);
Route::post('/teacherSignUp', [AuthController::class, 'registerAsTeacher']);
Route::post('/teacherlogin', [AuthController::class, 'teacherlogin']);

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
        // Route::get('/showItem/{id}/users', 'show')->name('Class.show');
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
        Route::get('/index', [SubjectController::class, 'index']);
        Route::get('/showItem/{id}', [SubjectController::class, 'show']);
        Route::get('/subforclass/{id}', [SubjectController::class, 'subjecstForClass']);
        Route::post('/updateSubject/{id}', [SubjectController::class, 'update']);
        Route::get('/ClassForSubjects/{id}', [SubjectController::class, 'indexClassSubjects']);
    });

Route::controller(AttendanceController::class)
<<<<<<< HEAD
->prefix('attendance')
->middleware(['auth:sanctum'])
->group(function ()
{
    Route::get('/allAttendances', [AttendanceController::class, 'index']);
    Route::post('/teachers', [AttendanceController::class, 'store_admins_and_teachers']);
    Route::post('/students', [AttendanceController::class, 'store_teachers_students_attendance']);
    Route::get('/showItem/{id}', [AttendanceController::class, 'show']);
    Route::post('/updateItem/{id}', [AttendanceController::class, 'update']);
    Route::delete('/deleteItem/{id}', [AttendanceController::class, 'destroy']);

    Route::middleware(['role:admin'])->group(function (){

    });

    Route::middleware(['role:teacher', 'role:admin'])->group(function (){

    });
});
=======
    ->prefix('attendance')
    // ->middleware('auth')
    ->group(function () {
        Route::get('/allAttendances', [AttendanceController::class, 'index']);
    });
>>>>>>> 93fe102198fdeedfbd7bb67bb0b45133fb2ab540

Route::get('/allSchedules', [ScheduleController::class, 'index']);
Route::get('/allAssignments', [AssignmentController::class, 'index']);
Route::get('/allDocuments', [DocumentController::class, 'index']);
Route::get('/allExamentions', [ExamentionController::class, 'index']);
Route::get('/allExamResults', [ExamResultController::class, 'index']);
Route::get('/allFees', [FeeController::class, 'index']);



Route::controller(ExamentionController::class)
    ->prefix('examention')
    ->group(function () {
        Route::get('/index', 'index');
        Route::get('/show/{id}', 'show');
        Route::post('/store', 'store');
        Route::put('/update/{id}','update');
    });
