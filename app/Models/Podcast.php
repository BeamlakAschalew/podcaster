<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Podcast extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'topic',
        'tone',
        'duration_minutes',
        'status',
    ];

    protected static function booted(): void
    {
        static::creating(function (Podcast $podcast) {
            if (empty($podcast->slug)) {
                $podcast->slug = static::generateUniqueSlug($podcast->title);
            }
        });
    }

    public static function generateUniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base.'-'.Str::random(5);
        }

        return $slug;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scripts(): HasMany
    {
        return $this->hasMany(PodcastScript::class);
    }

    public function audioSegments(): HasMany
    {
        return $this->hasMany(PodcastAudioSegment::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(PodcastFile::class);
    }

    public function outputs(): HasMany
    {
        return $this->hasMany(PodcastOutput::class);
    }
}
