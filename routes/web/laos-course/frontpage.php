<?php

use App\Http\Controllers\LaosCourse\Guest\LandingController;
use Illuminate\Support\Facades\Route;

Route::get('/course', [LandingController::class, 'index']);

?>