<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $category_id
 * @property string|null $description
 * @property string $amount
 * @property int|null $payment_method_id
 * @property string|null $payment_reference
 * @property string $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ExpenseCategory $category
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense wherePaymentReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expense whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Expense extends Model
{
    protected $appends = ['total'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    public function total(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->amount * $this->qty
        );
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
