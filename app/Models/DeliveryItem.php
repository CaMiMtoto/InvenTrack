<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $delivery_id
 * @property int $order_item_id
 * @property int $quantity
 * @property string $status
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Delivery $delivery
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryItem whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryItem whereDeliveryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryItem whereOrderItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryItem whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryItem whereUpdatedAt($value)
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
