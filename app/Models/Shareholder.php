<?php

namespace App\Models;

use App\Traits\HasEncodedId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $legal_type_id
 * @property string $id_number
 * @property string $name
 * @property string $phone_number
 * @property string $email
 * @property string|null $tin
 * @property string|null $birth_date
 * @property string|null $nationality
 * @property string|null $residential_address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $user_id
 * @property-read \App\Models\LegalType $legalType
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Share> $shares
 * @property-read int|null $shares_count
 * @method static \Database\Factories\ShareholderFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareholder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareholder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareholder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareholder whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareholder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareholder whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareholder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareholder whereIdNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareholder whereLegalTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareholder whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareholder whereNationality($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareholder wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareholder whereResidentialAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareholder whereTin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareholder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shareholder whereUserId($value)
 * @mixin \Eloquent
 */
class Shareholder extends Model
{
    use HasFactory,HasEncodedId;
    public function legalType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LegalType::class);
    }

    public function shares()
    {
        return $this->hasMany(Share::class);
    }
}
