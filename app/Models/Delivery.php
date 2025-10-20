<?php

namespace App\Models;

use App\Constants\Permission;
use App\Constants\Status;
use App\Traits\HasEncodedId;
use App\Traits\HasStatusColor;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $order_id
 * @property int $delivery_person_id
 * @property string $status
 * @property string|null $notes
 * @property string|null $delivered_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $deliveryPerson
 * @property-read string $status_color
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DeliveryItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Order $order
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ReturnModel> $returns
 * @property-read int|null $returns_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Delivery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Delivery newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Delivery query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Delivery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Delivery whereDeliveredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Delivery whereDeliveryPersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Delivery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Delivery whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Delivery whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Delivery whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Delivery whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Delivery extends Model
{
    use HasStatusColor, HasEncodedId;

    protected $appends = ['status_color'];
    protected $guarded = [];

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function deliveryPerson(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_person_id');
    }

    public function items(): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\HasMany|Delivery
    {
        return $this->hasMany(DeliveryItem::class);
    }


    public function status(): Attribute
    {
        return Attribute::make(
            get: fn() => ucwords(
                \Str::of($this->attributes['status'])
            )
        );
    }

    public function returns(): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\HasMany|Delivery
    {
        return $this->hasMany(ReturnModel::class, 'delivery_id');
    }

    public function statusCanBeUpdated(): bool
    {
        if (strtolower($this->status) != strtolower(Status::PartiallyDelivered)
            && strtolower($this->status) != strtolower(Status::Delivered)
            && auth()->user()->can(Permission::DELIVER_PRODUCTS)
            && $this->delivery_person_id == auth()->id()
        )
            return true;
        return false;
    }
}
