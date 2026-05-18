<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotels extends Model
{
    use HasFactory;

    protected $table = 'hotel_bookings';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'hotel_id',
        'user_id',
        'check_in',
        'check_out',
        'adults',
        'childrens',
        'transaction_id',
        'price',
        'rooms',
        'travelling_from',
        'guest_details',
        'payment_via',
        'status',
        'total_amount',
    ];
    
    protected $casts = [
    'guest_details' => 'array',
];


    /**
     * Relationship: Booking belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: Booking belongs to a Hotel (ModulesData)
     */
    public function hotel()
    {
        return $this->belongsTo(ModulesData::class, 'hotel_id');
    }
}