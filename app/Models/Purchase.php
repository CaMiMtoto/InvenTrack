<?php

namespace App\Models;

use App\Traits\HasEncodedId;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property int $supplier_id
 * @property string|null $invoice_number
 * @property string $total_amount
 * @property \Illuminate\Support\Carbon $purchased_at
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PurchaseItem> $items
 * @property-read int|null $items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JournalEntry> $journalEntries
 * @property-read int|null $journal_entries_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StockMovement> $stockMovements
 * @property-read int|null $stock_movements_count
 * @property-read \App\Models\Supplier $supplier
 * @property-read mixed $total
 * @method static \Database\Factories\PurchaseFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereInvoiceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase wherePurchasedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Purchase whereUserId($value)
 * @mixin \Eloquent
 */
class Purchase extends Model   implements Auditable
{
    use HasFactory, HasEncodedId,\OwenIt\Auditing\Auditable;

    protected $casts = [
        'purchased_at' => 'datetime'
    ];

    public function items(): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function supplier(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }


    public static function booted(): void
    {
        static::creating(function ($purchase) {
            $year = now()->year;

            // Get last purchase in current year
            $lastInvoice = self::whereYear('created_at', $year)
                ->orderBy('id', 'desc')
                ->first();

            $lastNumber = $lastInvoice
                ? intval(substr($lastInvoice->invoice_number, -4))
                : 0;

            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

            $purchase->invoice_number = "INV-$year-$nextNumber";
        });
    }

    public function generateInvoiceNumber(): string
    {
        $str = $this->id;
        $padded = str_pad($str, 5, '0', STR_PAD_LEFT);
        $invNo = 'PO-' . $padded;
        return $this->update(['invoice_number' => $invNo]);
    }

    public function stockMovements(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(StockMovement::class, 'reference');
    }

    public function journalEntries(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(JournalEntry::class, 'reference');
    }

    public function total(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->items->sum('total')
        );
    }

}
