<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $stock_adjustment_id
 * @property int $product_id
 * @property int $quantity
 * @property int $quantity_before
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $type
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\StockAdjustment $stockAdjustment
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAdjustmentItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAdjustmentItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAdjustmentItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAdjustmentItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAdjustmentItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAdjustmentItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAdjustmentItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAdjustmentItem whereQuantityBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAdjustmentItem whereStockAdjustmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAdjustmentItem whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockAdjustmentItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StockAdjustmentItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function stockAdjustment(): BelongsTo
    {
        return $this->belongsTo(StockAdjustment::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}