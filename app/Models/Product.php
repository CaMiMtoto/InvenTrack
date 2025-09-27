<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function increaseStock(int $qty): void
    {
        $this->increment('stock', $qty);
    }

    public function decreaseStock(int $qty): void
    {
        $this->decrement('stock', $qty);
    }
}
