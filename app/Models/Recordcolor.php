<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recordcolor extends Model
{
    use HasFactory;

    protected $table = 'recordcolors';
    public $timestamps = false;
    protected $guarded = [];
}
