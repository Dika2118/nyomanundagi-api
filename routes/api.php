<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\HeroBannerController;
use App\Http\Controllers\Api\ProjectCategoryController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ProjectImageController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SiteStatController;
use App\Http\Controllers\Api\TeamMemberController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Auth routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Resource routes
Route::apiResource('hero-banners', HeroBannerController::class);
Route::apiResource('services', ServiceController::class);
Route::apiResource('project-categories', ProjectCategoryController::class);
Route::apiResource('customers', CustomerController::class);
Route::apiResource('projects', ProjectController::class);
Route::apiResource('project-images', ProjectImageController::class);
Route::apiResource('team-members', TeamMemberController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('blogs', BlogController::class);
Route::apiResource('site-stats', SiteStatController::class);
