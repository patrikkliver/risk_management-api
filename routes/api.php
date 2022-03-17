<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('incident/new_incident', [IncidentController::class, 'new_incident']);
Route::get('incident/is_done', [IncidentController::class, 'is_done']);
Route::get('incident/is_done/{id}', [TaskController::class, 'show']);

Route::apiResources([
    'incident' => IncidentController::class,
    'task' => TaskController::class,
    'comment' => CommentController::class
]);