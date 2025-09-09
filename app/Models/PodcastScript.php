<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PodcastScript extends Model
{
    protected $fillable = [
        'podcast_id',
        'raw_text',
        'final_text',
        'speaker',
    ];
}
