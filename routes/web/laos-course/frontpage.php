<?php

use Illuminate\Support\Facades\Route;

Route::get('/course', function()
{
    return view('laos-course.layouts.guest');
});

?>