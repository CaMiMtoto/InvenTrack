<?php

namespace App\Http\Controllers;

use App\Models\Cell;
use App\Models\District;
use App\Models\Sector;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function getSectors(District $district)
    {
        return $district->sectors()->get();
    }

    public function getCells(Sector $sector)
    {
        return $sector->cells()->get();
    }

    public function getVillages(Cell $cell)
    {
        return $cell->villages()->get();
    }
}
