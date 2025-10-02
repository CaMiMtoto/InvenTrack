<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $approvable_type
 * @property int $approvable_id
 * @property int $user_id
 * @property string $status
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $approvable
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalFlow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalFlow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalFlow query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalFlow whereApprovableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalFlow whereApprovableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalFlow whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalFlow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalFlow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalFlow whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalFlow whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ApprovalFlow whereUserId($value)
 * @mixin \Eloquent
 */
class ApprovalFlow extends Model
{
    protected $guarded = [];

    public function approvable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
