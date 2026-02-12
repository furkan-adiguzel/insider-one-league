<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LeagueController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\SimulationController;

Route::get('/league', [LeagueController::class, 'show']);

Route::get('/teams', [TeamController::class, 'index']);
Route::post('/teams', [TeamController::class, 'store']);
Route::patch('/teams/{teamId}', [TeamController::class, 'update']);
Route::delete('/teams/{teamId}', [TeamController::class, 'destroy']);

Route::get('/fixtures', [MatchController::class, 'fixtures']);

Route::post('/simulation/generate-fixtures', [SimulationController::class, 'generateFixtures']);
Route::post('/simulation/play-next-week', [SimulationController::class, 'playNextWeek']);
Route::post('/simulation/play-all', [SimulationController::class, 'playAllWeeks']);
Route::post('/simulation/reset', [SimulationController::class, 'resetAll']);
Route::patch('/simulation/matches/{matchId}', [SimulationController::class, 'editMatch']);
