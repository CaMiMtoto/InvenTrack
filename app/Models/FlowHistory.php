<?php

namespace App\Models;

use App\Traits\HasStatusColor;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $reference_type
 * @property int $reference_id
 * @property string $action
 * @property string|null $comment
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowHistory whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowHistory whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowHistory whereReferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowHistory whereReferenceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowHistory whereUserId($value)
 * @mixin \Eloquent
 */
class FlowHistory extends Model
{
    use HasStatusColor;
    public function reference(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
