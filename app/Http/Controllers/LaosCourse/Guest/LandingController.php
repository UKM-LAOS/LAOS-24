<?php

namespace App\Http\Controllers\LaosCourse\Guest;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        return view('laos-course.pages.guest.landing-page.index', [
            'courses' => Course::whereIsDraft(false)->latest()->limit(3)->get(),
            'categories' => CourseCategory::with(['media'])->withCount('courses')->get()
        ]);
    }
}
