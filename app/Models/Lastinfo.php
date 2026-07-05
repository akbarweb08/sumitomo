<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lastinfo extends Model
{
    use HasFactory;

    protected $table = 'lastinfo';
    public $timestamps = false;
    protected $guarded = [];
}
