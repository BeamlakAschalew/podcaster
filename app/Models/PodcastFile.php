<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PodcastFile extends Model
{
    protected $fillable = [
        'podcast_id',
        'original_name',
        'file_path',
        'file_type',
        'file_size',
    ];

    public function podcast(): BelongsTo
    {
        return $this->belongsTo(Podcast::class);
    }
}
