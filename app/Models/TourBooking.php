<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Tours;
use App\Models\Country;

class TourBooking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'tour_id',
        'booking_reference',
        'adults',
        'children',
        'departure_date',
        'total_amount',
        'adult_price',
        'children_price',
        'payment_method',
        'payment_status',
        'payment_id',
        'passenger_details',
        'contact_details',
        'status',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'total_amount' => 'decimal:2',
        'adult_price' => 'decimal:2',
        'children_price' => 'decimal:2',
        'passenger_details' => 'array',
        'contact_details' => 'array',
    ];

    /**
     * Get the user that owns the booking
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tour for this booking
     */
    public function tour()
    {
        return $this->belongsTo(Tours::class, 'tour_id');
    }

    /**
     * Get passenger details as array with resolved country names
     */
    public function getPassengersAttribute()
    {
        $passengers = [];
        if (is_string($this->passenger_details)) {
            $passengers = json_decode($this->passenger_details, true) ?? [];
        } else {
            $passengers = $this->passenger_details ?? [];
        }

        // Resolve country names from IDs
        foreach ($passengers as &$passenger) {
            if (isset($passenger['country']) && is_numeric($passenger['country'])) {
                $country = Country::find($passenger['country']);
                $passenger['country_name'] = $country ? $country->name : 'Unknown';
            } else {
                $passenger['country_name'] = $passenger['country'] ?? 'Unknown';
            }
        }

        return $passengers;
    }

    /**
     * Get contact details as array
     */
    public function getContactAttribute()
    {
        if (is_string($this->contact_details)) {
            return json_decode($this->contact_details, true) ?? [];
        }
        return $this->contact_details ?? [];
    }

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAttribute()
    {
        return \App\Helpers\CurrencyHelper::formatPrice($this->total_amount);
    }

    /**
     * Get formatted departure date
     */
    public function getFormattedDepartureDateAttribute()
    {
        return $this->departure_date->format('d M Y');
    }

    /**
     * Check if payment is completed
     */
    public function isPaid()
    {
        return $this->payment_status === 'completed';
    }

    /**
     * Check if booking is confirmed
     */
    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        switch ($this->status) {
            case 'confirmed':
                return 'badge-success';
            case 'cancelled':
                return 'badge-danger';
            case 'completed':
                return 'badge-info';
            default:
                return 'badge-secondary';
        }
    }

    /**
     * Get payment status badge class
     */
    public function getPaymentStatusBadgeClassAttribute()
    {
        switch ($this->payment_status) {
            case 'completed':
                return 'badge-success';
            case 'pending':
                return 'badge-warning';
            case 'failed':
                return 'badge-danger';
            case 'cancelled':
                return 'badge-secondary';
            default:
                return 'badge-secondary';
        }
    }

    /**
     * Scope for user's bookings
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for confirmed bookings
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope for paid bookings
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'completed');
    }

    /**
     * Scope for upcoming bookings
     */
    public function scopeUpcoming($query)
    {
        return $query->where('departure_date', '>=', now()->toDateString());
    }

    /**
     * Scope for past bookings
     */
    public function scopePast($query)
    {
        return $query->where('departure_date', '<', now()->toDateString());
    }
}
