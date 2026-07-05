<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boxbackup extends Model
{
    use HasFactory;

    protected $table = 'boxbackup';
    public $timestamps = false;
    protected $guarded = [];
}
