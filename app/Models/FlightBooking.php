<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'offer_id',
        'booking_reference',
        'trip_type',
        'departure_date',
        'return_date',
        'origin_code',
        'destination_code',
        'adults',
        'children',
        'total_amount',
        'currency',
        'passenger_details',
        'payment_status',
        'payment_method',
        'transaction_id',
        'booking_status'
    ];

    protected $casts = [
        'departure_date' => 'datetime',
        'return_date' => 'datetime',
        'passenger_details' => 'array',
        'total_amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 