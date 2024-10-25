<?php

use Illuminate\Support\Facades\Route;

require __DIR__.'/web/laos-course/frontpage.php';

Route::get('/', function () {
    return view('welcome');
});
