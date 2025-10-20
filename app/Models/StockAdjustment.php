<?php

namespace App\Models;

use App\Traits\HasEncodedId;
use App\Traits\HasStatusColor;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string|null $reason
 * @property string $status
 * @property int $requested_by
 * @property int|null $reviewed_by
 * @property string|null $reviewed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $approver
 * @property-read string $status_color
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StockAdjustmentItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\User $requester
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAdjustment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAdjustment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAdjustment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAdjustment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAdjustment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAdjustment whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAdjustment whereRequestedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAdjustment whereReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAdjustment whereReviewedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAdjustment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAdjustment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StockAdjustment extends Model
{
    use HasStatusColor,HasEncodedId;

    protected $guarded = [];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    protected $appends=['status_color'];

    public function items(): HasMany
    {
        return $this->hasMany(StockAdjustmentItem::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function status(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ucwords($value)
        );
    }
}
