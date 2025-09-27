<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read \App\Models\Delivery|null $delivery
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryItem query()
 * @mixin \Eloquent
 */
class DeliveryItem extends Model
{
    protected $guarded = [];

    public function delivery(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    protected static function booted(): void
    {
        static::saved(function (DeliveryItem $item) {
            if ($item->quantity_returned > 0) {
                // restore stock
                $item->product->increaseStock($item->quantity_returned);
            }
        });
    }
}
