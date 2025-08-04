<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TaskController;
use App\Http\Controllers\Api\V1\EducationalCenterController;
use App\Http\Controllers\Api\V1\CourseController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('/', function () {
        return response()->json(['message' => 'Welcome to the API']);
    });

    // Task routes
    Route::apiResource('/tasks', TaskController::class);
    Route::apiResource('/educational-centers', EducationalCenterController::class);
    Route::apiResource('/courses', CourseController::class);
});
