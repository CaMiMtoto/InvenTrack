<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read \App\Models\User|null $deliveryPerson
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DeliveryItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Order|null $order
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Delivery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Delivery newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Delivery query()
 * @mixin \Eloquent
 */
class Delivery extends Model
{
    protected $guarded = [];

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function deliveryPerson(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_person_id');
    }

    public function items()
    {
        return $this->hasMany(DeliveryItem::class);
    }

}
