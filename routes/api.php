<?php

use App\Http\Controllers\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;


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



Route::middleware(['jwt.auth'])->group(function () {
    Route::post('/comments', [CommentController::class, 'store']); // Add comment
    Route::post('/comments/reply', [CommentController::class, 'storeReply']); // Add reply
    Route::get('/tasks/{task_id}/comments', [CommentController::class, 'getComments']); // Get comments for a task
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']); // Delete comment
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
