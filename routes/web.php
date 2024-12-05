<?php

use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', HomeController::class)->name('home');
Route::get('/videos/{video}', VideoController::class)->name('video.show');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::resource('/admin/videos', AdminVideoController::class)->except(['show']);
    Route::resource('/admin/tags', TagController::class)->except(['show']);
});
