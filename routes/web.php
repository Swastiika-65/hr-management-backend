<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\UserManagementController;
use App\Http\Controllers\AuthController;
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
    return view('welcome');
});


Route::middleware(['auth','role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {

    Route::get('/dashboard', function () {
        return view('superadmin.dashboard');
    })->name('dashboard');

    Route::get('/users', [UserManagementController::class, 'index'])
        ->name('users.index');

    Route::get('/users/create', [UserManagementController::class, 'create'])
        ->name('users.create');

    Route::post('/users', [UserManagementController::class, 'store'])
        ->name('users.store');
});

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');

// Route::post('/login2', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware(['auth','role:superadmin'])->group(function () {
    Route::get('/superadmin/dashboard', fn() => view('superadmin.dashboard'));
});

Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/admin/dashboard', fn() => view('admin.dashboard'));
});
