<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $return_id
 * @property int $product_id
 * @property int $return_reason_id
 * @property int $quantity
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\ReturnModel $returnModel
 * @property-read \App\Models\ReturnReason $returnReason
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnItem whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnItem whereReturnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnItem whereReturnReasonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ReturnItem extends Model
{
    protected $fillable = ['return_id', 'product_id', 'quantity', 'note'];

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }


    public function returnModel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ReturnModel::class,'return_id');
    }

    public function returnReason(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ReturnReason::class,'return_reason_id');
    }


}
