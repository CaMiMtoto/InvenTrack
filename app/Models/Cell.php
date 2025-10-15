<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cell extends Model
{
    //
    public function villages()
    {
        return $this->hasMany(Village::class);
    }
}
