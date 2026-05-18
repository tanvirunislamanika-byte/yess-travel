<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tours extends Model
{
    use HasFactory;

    protected $table = 'modules_data';
    protected $primaryKey = 'id';

    protected $fillable = [
        'module_id',
        'title',
        'slug',
        'description',
        'image',
        'images',
        'status',
        'extra_field_1',  // Tour start Date
        'extra_field_2',  // Tour End Date
        'extra_field_3',  // Number of Days
        'extra_field_4',  // Total Nights
        'extra_field_5',  // Select Departure Country
        'extra_field_6',  // Select Departure State
        'extra_field_7',  // Select Departure City
        'extra_field_8',  // Price Per Head For Adults
        'extra_field_9',  // Price For Kids 5 to 10 Years (Optional)
        'extra_field_10', // Select Tour Type (module_id 35)
        'extra_field_11', // Select Transport Type (module_id 36)
        'extra_field_12', // Tour Terms & Conditions
    ];

    protected $casts = [
        'images' => 'array',
        'extra_field_1' => 'date', // Tour start Date
        'extra_field_2' => 'date', // Tour End Date
        'extra_field_3' => 'integer', // Number of Days
        'extra_field_4' => 'integer', // Total Nights
        'extra_field_8' => 'decimal:2', // Price Per Head For Adults
        'extra_field_9' => 'decimal:2', // Price For Kids 5 to 10 Years
    ];

    // Boot method to set module_id automatically
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->module_id = 34; // Tours module ID
        });
    }

    // Scope for tours only
    public function scopeTours($query)
    {
        return $query->where('module_id', 34);
    }

    // Scope for active tours
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Relationships
    public function departureCountry()
    {
        return $this->belongsTo(Country::class, 'extra_field_5');
    }

    public function departureState()
    {
        return $this->belongsTo(State::class, 'extra_field_6');
    }

    public function departureCity()
    {
        return $this->belongsTo(City::class, 'extra_field_7');
    }

    public function tourType()
    {
        return $this->belongsTo(ModulesData::class, 'extra_field_10')->where('module_id', 35);
    }

    public function transportType()
    {
        return $this->belongsTo(ModulesData::class, 'extra_field_11')->where('module_id', 36);
    }

    // Accessors
    public function getTourStartDateAttribute()
    {
        return $this->extra_field_1;
    }

    public function getTourEndDateAttribute()
    {
        return $this->extra_field_2;
    }

    public function getNumberOfDaysAttribute()
    {
        return $this->extra_field_3;
    }

    public function getTotalNightsAttribute()
    {
        return $this->extra_field_4;
    }

    public function getDepartureCountryIdAttribute()
    {
        return $this->extra_field_5;
    }

    public function getDepartureStateIdAttribute()
    {
        return $this->extra_field_6;
    }

    public function getDepartureCityIdAttribute()
    {
        return $this->extra_field_7;
    }

    public function getAdultPriceAttribute()
    {
        return $this->extra_field_8;
    }

    public function getKidsPriceAttribute()
    {
        return $this->extra_field_9;
    }

    public function getTourTypeIdAttribute()
    {
        return $this->extra_field_10;
    }

    public function getTransportTypeIdAttribute()
    {
        return $this->extra_field_11;
    }

    public function getTermsConditionsAttribute()
    {
        return $this->extra_field_12;
    }

    // Helper methods
    public function getFormattedPriceAttribute()
    {
        return 'PKR ' . number_format($this->adult_price, 0);
    }

    public function getFormattedStartDateAttribute()
    {
        return $this->tour_start_date ? date('d M Y', strtotime($this->tour_start_date)) : 'N/A';
    }

    public function getFormattedEndDateAttribute()
    {
        return $this->tour_end_date ? date('d M Y', strtotime($this->tour_end_date)) : 'N/A';
    }

    public function getDurationTextAttribute()
    {
        if ($this->number_of_days && $this->total_nights) {
            return $this->number_of_days . ' Days / ' . $this->total_nights . ' Nights';
        }
        return 'N/A';
    }
}
