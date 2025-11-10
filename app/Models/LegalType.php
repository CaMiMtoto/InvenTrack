<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LegalType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LegalType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LegalType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LegalType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LegalType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LegalType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LegalType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LegalType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LegalType extends Model
{
    //
}
