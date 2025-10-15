<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    //
    public function cells()
    {
        return $this->hasMany(Cell::class);
    }
}
