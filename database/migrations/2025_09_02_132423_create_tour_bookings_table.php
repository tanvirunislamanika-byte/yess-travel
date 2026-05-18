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
        Schema::create('tour_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tour_id')->constrained('modules_data')->onDelete('cascade');
            $table->string('booking_reference')->unique();
            $table->date('tour_date');
            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);
            $table->decimal('adult_price', 10, 2);
            $table->decimal('children_price', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->enum('booking_status', ['confirmed', 'cancelled', 'completed'])->default('confirmed');
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->text('special_requests')->nullable();
            $table->json('guest_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_bookings');
    }
};