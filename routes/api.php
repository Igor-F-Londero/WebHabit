<?php

use App\Http\Controllers\Api\CheckinController;
use App\Http\Controllers\Api\HabitController;
use App\Http\Controllers\Api\StatsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'active'])->group(function () {
    Route::get('/habits', [HabitController::class, 'index']);
    Route::post('/checkins', [CheckinController::class, 'store']);
    Route::get('/stats', [StatsController::class, 'index']);
});
