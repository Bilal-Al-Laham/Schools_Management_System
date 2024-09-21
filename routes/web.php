<?php

use App\Http\Controllers\Api\AssignmentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    config('services.mailgun.domain');
    return view('welcome');
});

Route::get('/hello', [AssignmentController::class, 'index']);

Route::view('/blog', 'index', ['name' => 'Bilal']);