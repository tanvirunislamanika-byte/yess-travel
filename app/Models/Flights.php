<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flights extends Model
{
    use HasFactory;

    public $table = 'flight_bookings';

    public function user($value='')
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
