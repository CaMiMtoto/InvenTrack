<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $delivery_id
 * @property int $order_item_id
 * @property int $quantity
 * @property int $delivered_quantity
 * @property int $returned_quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Delivery $delivery
 * @property-read \App\Models\OrderItem $orderItem
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryItem whereDeliveredQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryItem whereDeliveryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryItem whereOrderItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DeliveryItem whereReturnedQuantity($value)
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

    public function orderItem(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
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
