<?php

use App\Http\Controllers\Api\VideoCommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/videos/{video}/comments', [VideoCommentController::class, 'index']);
Route::post('/videos/{video}/comments/{user}', [VideoCommentController::class, 'store']);
