<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string|null $code
 * @property string $name
 * @property string|null $description
 * @property int $restockable
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnReason newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnReason newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnReason query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnReason whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnReason whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnReason whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnReason whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnReason whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnReason whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnReason whereRestockable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReturnReason whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ReturnReason extends Model
{
    //
}
