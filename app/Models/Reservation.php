<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['resource_id', 'reserved_at', 'duration', 'status'];

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
