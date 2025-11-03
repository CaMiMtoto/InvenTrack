<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Share extends Model
{
    public function shareholder(): BelongsTo
    {
        return $this->belongsTo(Shareholder::class);
    }
}
