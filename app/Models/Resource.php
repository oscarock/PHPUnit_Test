<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'capacity'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
