<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PodcastAudioSegment extends Model
{
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (is_null($model->order)) {
                $model->order = static::where('podcast_id', $model->podcast_id)->max('order') + 1;
            }
        });
    }
}
