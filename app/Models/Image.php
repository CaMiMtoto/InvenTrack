<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['path', 'alt_text', 'is_primary'];

    public function imageable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    // Accessor for full URL
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}
