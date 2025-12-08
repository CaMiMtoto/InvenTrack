<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property float $rate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductClass newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductClass newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductClass query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductClass whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductClass whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductClass whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductClass whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductClass whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductClass extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'rate',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
