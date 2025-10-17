<?php

namespace App\Traits;

use App\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Builder;

trait HasActiveScope
{
    /**
     * Boot the active scope for a model.
     *
     * @return void
     */
    protected static function bootHasActiveScope(): void
    {
        static::addGlobalScope(new ActiveScope);
    }

    /**
     * Get all the models including inactive ones.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithInactive(Builder $query): Builder
    {
        return $query->withoutGlobalScope(ActiveScope::class);
    }
}
