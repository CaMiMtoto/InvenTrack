<?php

namespace App\Models;

use App\Traits\HasEncodedId;
use App\Traits\HasStatusColor;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $delivery_id
 * @property int|null $order_id
 * @property int $done_by
 * @property string|null $reason
 * @property string $status
 * @property string|null $notes
 * @property int|null $reviewed_by
 * @property \Illuminate\Support\Carbon|null $reviewed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $approver
 * @property-read \App\Models\Delivery $delivery
 * @property-read \App\Models\User $doneBy
 * @property-read string $status_color
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ReturnItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Order|null $order
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel whereDeliveryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel whereDoneBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel whereReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel whereReviewedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ReturnModel extends Model
{
    use HasStatusColor,HasEncodedId;

    protected $table = 'returns';
    protected $appends=['status_color'];

    protected $casts=[
        'reviewed_at'=>'datetime'
    ];

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
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function doneBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'done_by');

    }

    public function getStatusAttribute(): string
    {
        return ucfirst($this->attributes['status']);
    }

}
