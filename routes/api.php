<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LoginLogController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMakeController;
use App\Http\Controllers\SuperAdmin\UserManagementController;
use App\Http\Controllers\SuperAdminController\RoleController;
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
Route::post('/register2',[AuthController::class, 'register2']);
Route::post('/login2', [AuthController::class, 'login2']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout2', [AuthController::class, 'logout2']);
});
// Route::post('/project',[ProjectController::class,'assign']);
// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/project1', [MailController::class, 'send']);
// });
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/project2', [LoginLogController::class, 'login']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/project3', [LoginLogController::class, 'logout']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/project4', [LeaveController::class, 'apply']);
});
// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/project5', [LeaveController::class, 'approve']);
// });
Route::middleware(['auth:sanctum','superadmin'])->group(function () {
    Route::post('/roles', [RoleController::class, 'updateRole']);
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/project', [projectController::class, 'assign']);
});
Route::middleware(['auth:sanctum'])->group(function () {
Route::post('/projectassign', [ProjectMakeController::class, 'store']);
});
// Route::middleware(['auth:sanctum'])->group(function () {
// Route::post('/group', [GroupController::class, 'sendMessage']);
// });
