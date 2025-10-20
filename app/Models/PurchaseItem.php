<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;


/**
 * @property int $id
 * @property int $purchase_id
 * @property int $product_id
 * @property int $quantity
 * @property string $unit_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $expiration_date
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Purchase $purchase
 * @property-read mixed $total
 * @method static \Database\Factories\PurchaseItemFactory factory($count = null, $state = [])
 * @method static Builder<static>|PurchaseItem newModelQuery()
 * @method static Builder<static>|PurchaseItem newQuery()
 * @method static Builder<static>|PurchaseItem query()
 * @method static Builder<static>|PurchaseItem whereCreatedAt($value)
 * @method static Builder<static>|PurchaseItem whereExpirationDate($value)
 * @method static Builder<static>|PurchaseItem whereId($value)
 * @method static Builder<static>|PurchaseItem whereProductId($value)
 * @method static Builder<static>|PurchaseItem wherePurchaseId($value)
 * @method static Builder<static>|PurchaseItem whereQuantity($value)
 * @method static Builder<static>|PurchaseItem whereUnitPrice($value)
 * @method static Builder<static>|PurchaseItem whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PurchaseItem extends Model
{
    use HasFactory;

    protected $casts = [
        'expiration_date' => 'date'
    ];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function total(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->quantity * $this->unit_price
        );
    }
}
