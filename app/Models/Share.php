<?php

namespace App\Models;

use App\Traits\HasEncodedId;
use App\Traits\HasStatusColor;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $shareholder_id
 * @property string $value
 * @property int $quantity
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $reviewed_by
 * @property \Illuminate\Support\Carbon|null $reviewed_at
 * @property int|null $user_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FlowHistory> $flowHistories
 * @property-read int|null $flow_histories_count
 * @property-read string $status_color
 * @property-read \App\Models\Payment|null $payment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \App\Models\Shareholder $shareholder
 * @property-read mixed $total
 * @method static \Database\Factories\ShareFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Share newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Share newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Share query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Share whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Share whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Share whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Share whereReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Share whereReviewedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Share whereShareholderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Share whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Share whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Share whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Share whereValue($value)
 * @mixin \Eloquent
 */
class Share extends Model
{
    use HasStatusColor, HasEncodedId,HasFactory;

    protected $appends = ['status_color', 'total', 'salary_amount'];
    protected $casts = [
        'reviewed_at' => 'datetime',
        'share_salary_percentage' => 'decimal:2',
    ];

    // Allow setting percentage at creation/approval time through mass assignment where appropriate
    protected $fillable = [
        'shareholder_id', 'value', 'quantity', 'status', 'reviewed_by', 'reviewed_at', 'user_id', 'share_salary_percentage'
    ];

    public function shareholder(): BelongsTo
    {
        return $this->belongsTo(Shareholder::class);
    }

    public function total(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->quantity * $this->value
        );
    }

    /**
     * Computed salary amount for this share (value * share_salary_percentage / 100)
     */
    public function salaryAmount(): Attribute
    {
        return Attribute::make(
            get: fn() => ($this->value * $this->quantity) * (($this->share_salary_percentage ?? config('shares.default_salary_percent', 0)) / 100)
        );
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function payment(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(Payment::class, 'paymentable');
    }

    public function flowHistories(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(FlowHistory::class, 'reference');
    }

}
