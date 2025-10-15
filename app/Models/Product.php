<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $category_id
 * @property string|null $description
 * @property string $price
 * @property int $stock
 * @property string|null $image
 * @property int $min_stock
 * @property int $is_active
 * @property string|null $unit_measure
 * @property string|null $sku
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category $category
 * @property-read int $actual_qty
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Image> $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PurchaseItem> $purchases
 * @property-read int|null $purchases_count
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereMinStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUnitMeasure($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Product extends Model
{
    use HasFactory;

    const IMAGE_PATH = 'images/products';

    protected $appends = ['actual_qty'];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function increaseStock(int $qty): void
    {
        $this->increment('stock', $qty);
    }

    public function decreaseStock(int $qty): void
    {
        $this->decrement('stock', $qty);
    }

    public function getActualQtyAttribute(): int
    {
        return $this->stock;
    }

    public static function generateSku($name, $categoryId): string
    {
        // Get first 3 letters of category
        $category = Category::find($categoryId);
        $catCode = strtoupper(substr($category->name, 0, 3));

        // Get first word or initials from product name
        $words = explode(' ', $name);
        $prodCode = strtoupper(substr($words[0], 0, 3));

        // Count existing products with same code pattern
        $count = self::where('sku', 'like', "$catCode-$prodCode-%")->count();

        // Increment by 1
        $number = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return "$catCode-$prodCode-$number";
    }

    public function images(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function purchases(): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\HasMany|Product
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
