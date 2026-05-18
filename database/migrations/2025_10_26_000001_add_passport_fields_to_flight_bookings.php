<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Note: passport information is already stored in the passenger_details JSON column
        // This migration is for reference and can add individual columns if needed
        // The passenger_details JSON column already handles all passport fields
        
        // If you want to add separate columns for reporting or indexing purposes, uncomment below:
        /*
        Schema::table('flight_bookings', function (Blueprint $table) {
            // These columns are optional as data is stored in passenger_details JSON
            // Only add if you need to query/index these fields separately
        });
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('flight_bookings', function (Blueprint $table) {
        //     // Rollback if needed
        // });
    }
};

