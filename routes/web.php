<?php

use App\Http\Controllers\MeetingController;
use App\Http\Controllers\AttendEventController;
use App\Models\Meeting;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('home');

Route::post('/attend-event', [AttendEventController::class, 'attend'])->name('attendevents.attend');


Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/events', [MeetingController::class, 'index'])->name('meetings.index');
    Route::post('/events', [MeetingController::class, 'store'])->name('meetings.store');
    Route::get('/events/create', [MeetingController::class, 'create'])->name('meetings.create');
    Route::get('/events/{meeting}', [MeetingController::class, 'show'])->name('meetings.show');
    Route::match(array('PUT', 'PATCH'),'/events/{meeting}', [MeetingController::class, 'update'])->name('meetings.update');
    Route::delete('/events/{meeting}', [MeetingController::class, 'destroy'])->name('meetings.destroy');
    Route::get('/events/{meeting}/edit', [MeetingController::class, 'edit'])->name('meetings.edit');


});
