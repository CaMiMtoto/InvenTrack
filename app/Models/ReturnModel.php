<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $delivery_id
 * @property int|null $order_id
 * @property int $done_by
 * @property string|null $reason
 * @property string $status
 * @property string|null $notes
 * @property int|null $approved_by
 * @property string|null $approved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $approver
 * @property-read \App\Models\Delivery $delivery
 * @property-read \App\Models\User|null $deliveryPerson
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ReturnItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Order|null $order
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel whereDeliveryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel whereDoneBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ReturnModel extends Model
{
    protected $table = 'returns';
    protected $fillable = ['delivery_id', 'order_id', 'done_by', 'reason', 'status'];

    public function items(): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ReturnItem::class, 'return_id');
    }

    public function delivery(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Delivery::class);
    }

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function approver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function deliveryPerson(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_person_id');
    }
}
