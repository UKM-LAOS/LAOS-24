<?php

use App\Http\Controllers\LaosCourse\Guest\LandingController;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::get('/course', [LandingController::class, 'index'])->name('laos-course.frontpage.index');
Route::get('/course/{slug}', [LandingController::class, 'show'])->name('laos-course.frontpage.show');

?>