<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Course extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = ['id'];

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function courseCategory()
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id');
    }

    public function courseChapters()
    {
        return $this->hasMany(CourseChapter::class);
    }

    public function courseStacks()
    {
        return $this->belongsToMany(CourseStack::class)->withPivot('course_id', 'course_stack_id');
    }

    public function courseReviews()
    {
        return $this->hasMany(CourseReview::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('course-thumbnail')
            ->singleFile();
        $this->addMediaCollection('course-gallery');
    }
}
