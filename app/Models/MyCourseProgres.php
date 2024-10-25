<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyCourseProgres extends Model
{
    protected $guarded = ['id'];

    public function myCourse()
    {
        return $this->belongsTo(MyCourse::class);
    }

    public function courseLesson()
    {
        return $this->belongsTo(CourseLesson::class);
    }
}
