<?php

namespace App\Models;

use App\Traits\HasEncodedId;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
    protected $appends = ['name'];
    /**
     * Fillable attributes for mass assignment
     * Include new file columns: photo and id_attachment
     */
    protected $fillable = [
        'first_name', 'last_name', 'legal_type_id', 'id_number', 'phone_number', 'email',
        'tin', 'birth_date', 'nationality', 'residential_address', 'province_id', 'district_id',
        'sector_id', 'cell_id', 'village_id', 'user_id', 'name', 'photo', 'id_attachment'
    ];

    public function legalType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LegalType::class);
    }
    public function name(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                // Prefer the separate first_name/last_name columns when available,
                // fall back to the stored name column for older rows.
                $first = $this->first_name ?? '';
                $last = $this->last_name ?? '';
                $combined = trim(trim($first) . ' ' . trim($last));
                return $combined !== '' ? $combined : ($value ?? null);
            }
        );
    }
    public function shares()
    {
        return $this->hasMany(Share::class);
    }
}
