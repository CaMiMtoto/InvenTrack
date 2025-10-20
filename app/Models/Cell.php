<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $sector_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Village> $villages
 * @property-read int|null $villages_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cell newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cell newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cell query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cell whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cell whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cell whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cell whereSectorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cell whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Cell extends Model
{
    //
    public function villages()
    {
        return $this->hasMany(Village::class);
    }
}
