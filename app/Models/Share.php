<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
 * @property-read \App\Models\Shareholder $shareholder
 * @property-read mixed $total
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Share newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Share newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Share query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Share whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Share whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Share whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Share whereShareholderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Share whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Share whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Share whereValue($value)
 * @mixin \Eloquent
 */
class Share extends Model
{
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
}
