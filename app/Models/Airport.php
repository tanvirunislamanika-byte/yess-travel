<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Airport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'iata_code',
        'country',
        'latitude',
        'longitude'
    ];

    protected $table = 'airports';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Remove if you want to use Laravel's default timestamp handling
    // public $timestamps = false;
} 