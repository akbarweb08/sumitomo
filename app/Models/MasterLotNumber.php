<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterLotNumber extends Model
{
    use HasFactory;

    protected $table = 'master_lot_numbers';
    protected $guarded = [];
}
