<?php

use Illuminate\Support\Facades\Route;

require __DIR__.'/web/course/frontpage.php';

Route::get('/', function () {
    return view('welcome');
});
