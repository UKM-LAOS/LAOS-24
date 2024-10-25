<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CourseStack extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = ['id'];

    public function courses() 
    {
        return $this->belongsToMany(Course::class)->withPivot('course_id', 'course_stack_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('course-stack-image')
            ->singleFile();
    }
}
