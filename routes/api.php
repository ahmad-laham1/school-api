<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function(){
    Route::post('login',[AuthController::class,'login']);
    Route::post('register',[AuthController::class,'register']);
    Route::middleware('auth:api')->group(function(){
        Route::post('logout',[AuthController::class,'logout']);
        Route::get('me',[AuthController::class,'me']);
    });
});

Route::middleware(['auth:api', 'role:admin,teacher'])->group(function () {
    Route::apiResource('classrooms', ClassroomController::class)->except(['index', 'show']);


});

// Students → admin + teacher can create
Route::middleware(['auth:api', 'role:admin,teacher'])->group(function () {
    Route::post('students', [StudentController::class, 'store']);
});

// Everyone with correct policy can still index/show/update
Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('classrooms', ClassroomController::class)->only(['index', 'show']);
    Route::apiResource('students', StudentController::class)->except(['store']);
    Route::get('/teachers', [UserController::class, 'teachers']);
Route::delete('/classrooms/{classroom}/students/{student}', [ClassroomController::class, 'removeStudent']);
});

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);

});


Route::middleware(['auth:api'])->group(function () {
    Route::get('/student/profile', [StudentController::class, 'profile']);
    Route::put('/student/profile', [StudentController::class, 'updateProfile']);
});

// Admin Dashboard
Route::middleware(['auth:api', 'role:admin'])->get('/admin/stats', [DashboardController::class, 'adminStats']);

// Teacher Dashboard
Route::middleware(['auth:api', 'role:teacher'])->get('/teacher/stats', [DashboardController::class, 'teacherStats']);

// Student Dashboard
Route::middleware(['auth:api', 'role:student'])->get('/student/stats', [DashboardController::class, 'studentStats']);