<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Article extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $guarded = ['id'];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('article-thumbnail')
            ->singleFile();
    }
}
