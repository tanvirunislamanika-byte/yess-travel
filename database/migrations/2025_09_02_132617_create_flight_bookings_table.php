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
        Schema::create('flight_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('flight_id')->constrained('modules_data')->onDelete('cascade');
            $table->string('booking_reference')->unique();
            $table->date('departure_date');
            $table->date('return_date')->nullable();
            $table->string('departure_airport');
            $table->string('arrival_airport');
            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);
            $table->integer('infants')->default(0);
            $table->decimal('adult_price', 10, 2);
            $table->decimal('children_price', 10, 2)->default(0);
            $table->decimal('infant_price', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->enum('trip_type', ['one_way', 'round_trip'])->default('one_way');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->enum('booking_status', ['confirmed', 'cancelled', 'completed'])->default('confirmed');
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->text('special_requests')->nullable();
            $table->json('passenger_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_bookings');
    }
};