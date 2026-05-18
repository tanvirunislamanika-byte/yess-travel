<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('flight_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('offer_id');
            $table->string('booking_reference')->nullable();
            $table->enum('trip_type', ['one-way', 'two-way']);
            $table->dateTime('departure_date');
            $table->dateTime('return_date')->nullable();
            $table->string('origin_code');
            $table->string('destination_code');
            $table->string('airline_code', 3)->nullable();
            $table->integer('adults');
            $table->integer('children');
            $table->decimal('total_amount', 10, 2);
            $table->string('currency', 3);
            $table->json('passenger_details');
            $table->enum('payment_status', ['pending', 'completed', 'failed'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->enum('booking_status', ['confirmed', 'cancelled', 'pending'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('flight_bookings');
    }
}; 