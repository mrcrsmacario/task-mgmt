<?php

use App\Http\Controllers\{ProfileController, TaskController};
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
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('task')->group(function() {
    Route::get('/', function() {
        return view('tasks.all_tasks');
    })->name('task.home');
    Route::get('/all-tasks', [TaskController::class, 'getTasks'])->name('task.all');
    Route::post('/create', [TaskController::class, 'create'])->name('task.create');
    Route::post('/update', [TaskController::class, 'update'])->name('task.update');
});

require __DIR__.'/auth.php';
