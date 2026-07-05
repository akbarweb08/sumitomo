<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverTask extends Model
{
    use HasFactory;

    protected $fillable = ['admin_id', 'driver_id', 'note', 'status'];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}
