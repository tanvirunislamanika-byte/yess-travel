<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Airport;

class AirportSeeder extends Seeder
{
    public function run()
    {
        $airports = [
            [
                'name' => 'London Heathrow Airport',
                'city' => 'London',
                'iata_code' => 'LHR',
                'country' => 'United Kingdom'
            ],
            [
                'name' => 'John F. Kennedy International Airport',
                'city' => 'New York',
                'iata_code' => 'JFK',
                'country' => 'United States'
            ],
            [
                'name' => 'Dubai International Airport',
                'city' => 'Dubai',
                'iata_code' => 'DXB',
                'country' => 'United Arab Emirates'
            ],
            [
                'name' => 'Singapore Changi Airport',
                'city' => 'Singapore',
                'iata_code' => 'SIN',
                'country' => 'Singapore'
            ],
            [
                'name' => 'Charles de Gaulle Airport',
                'city' => 'Paris',
                'iata_code' => 'CDG',
                'country' => 'France'
            ],
            [
                'name' => 'Los Angeles International Airport',
                'city' => 'Los Angeles',
                'iata_code' => 'LAX',
                'country' => 'United States'
            ],
            [
                'name' => 'Tokyo Haneda Airport',
                'city' => 'Tokyo',
                'iata_code' => 'HND',
                'country' => 'Japan'
            ],
            [
                'name' => 'Hong Kong International Airport',
                'city' => 'Hong Kong',
                'iata_code' => 'HKG',
                'country' => 'China'
            ],
            [
                'name' => 'Sydney Airport',
                'city' => 'Sydney',
                'iata_code' => 'SYD',
                'country' => 'Australia'
            ],
            [
                'name' => 'Amsterdam Airport Schiphol',
                'city' => 'Amsterdam',
                'iata_code' => 'AMS',
                'country' => 'Netherlands'
            ]
        ];

        foreach ($airports as $airport) {
            Airport::create($airport);
        }
    }
} 