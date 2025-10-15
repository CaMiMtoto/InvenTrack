<?php

namespace App\Models;

use App\Traits\HasEncodedId;
use Database\Factories\CustomerFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string|null $tin
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $address
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static CustomerFactory factory($count = null, $state = [])
 * @method static Builder<static>|Customer newModelQuery()
 * @method static Builder<static>|Customer newQuery()
 * @method static Builder<static>|Customer query()
 * @method static Builder<static>|Customer whereAddress($value)
 * @method static Builder<static>|Customer whereCreatedAt($value)
 * @method static Builder<static>|Customer whereEmail($value)
 * @method static Builder<static>|Customer whereId($value)
 * @method static Builder<static>|Customer whereName($value)
 * @method static Builder<static>|Customer wherePhone($value)
 * @method static Builder<static>|Customer whereTin($value)
 * @method static Builder<static>|Customer whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Customer extends Model
{
    use HasFactory,HasEncodedId;

    public function orders(): Customer|Builder|HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    public function cell(): BelongsTo
    {
        return $this->belongsTo(Cell::class);
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }


}
