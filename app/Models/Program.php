<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Program extends Model implements HasMedia
{
    use InteractsWithMedia;
    
    protected $guarded = ['id'];

    protected $casts = [
        'program_schedules' => 'array'
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }   

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('program-thumbnail')
            ->singleFile();
        $this->addMediaCollection('program-gallery');
    }
}
