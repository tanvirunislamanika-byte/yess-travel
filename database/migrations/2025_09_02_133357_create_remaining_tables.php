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
        // Assigned Contacts Table
        Schema::create('assigned_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->string('lead_status')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->text('task')->nullable();
            $table->timestamps();
        });

        // AUC Countries Table (Alternative countries table)
        Schema::create('auc_countries', function (Blueprint $table) {
            $table->id();
            $table->string('country_name', 200)->nullable();
            $table->string('currency_name', 50)->nullable();
            $table->string('continent', 100)->nullable();
            $table->string('country_code', 10)->nullable();
            $table->boolean('subdomain')->default(false);
            $table->tinyInteger('status')->default(1);
            $table->unsignedInteger('vat_percentage')->nullable();
            $table->string('time_zone', 50)->nullable();
            $table->string('time_difference', 20)->nullable();
            $table->string('2digit_code', 5)->nullable();
            $table->string('time_format', 20)->nullable();
            $table->string('flag', 200)->nullable();
            $table->integer('active_dst')->nullable();
            $table->integer('currency')->nullable();
            $table->unsignedInteger('dated')->nullable();
            $table->string('main_currency', 25)->nullable();
            $table->string('phone_code')->nullable();
            $table->string('currency_code')->nullable();
            $table->string('verify_phone', 25)->default('No');
            $table->timestamps();
        });

        // Fields Show In List Table
        Schema::create('fields_show_in_list', function (Blueprint $table) {
            $table->id();
            $table->string('module_name');
            $table->string('field_name');
            $table->boolean('is_visible')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Flight Booking Table (Alternative)
        Schema::create('flight_booking', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('flight_id');
            $table->string('booking_reference')->unique();
            $table->date('departure_date');
            $table->date('return_date')->nullable();
            $table->string('departure_airport');
            $table->string('arrival_airport');
            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);
            $table->integer('infants')->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->enum('booking_status', ['confirmed', 'cancelled', 'completed'])->default('confirmed');
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->text('passenger_details')->nullable();
            $table->timestamps();
        });

        // History Table
        Schema::create('history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('action');
            $table->text('description')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });

        // LTM Translations Table (Laravel Translation Manager)
        Schema::create('ltm_translations', function (Blueprint $table) {
            $table->id();
            $table->integer('status')->default(0);
            $table->string('locale');
            $table->string('group');
            $table->text('key');
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Regions Table
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Subregions Table
        Schema::create('subregions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            $table->foreignId('country_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // User Stats Table
        Schema::create('user_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('total_bookings')->default(0);
            $table->decimal('total_spent', 10, 2)->default(0);
            $table->integer('total_reviews')->default(0);
            $table->timestamp('last_booking_at')->nullable();
            $table->timestamps();
        });

        // Widget Pages Table
        Schema::create('widget_pages', function (Blueprint $table) {
            $table->id();
            $table->string('page_name');
            $table->string('page_slug')->unique();
            $table->text('content')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assigned_contacts');
        Schema::dropIfExists('auc_countries');
        Schema::dropIfExists('fields_show_in_list');
        Schema::dropIfExists('flight_booking');
        Schema::dropIfExists('history');
        Schema::dropIfExists('ltm_translations');
        Schema::dropIfExists('regions');
        Schema::dropIfExists('subregions');
        Schema::dropIfExists('user_stats');
        Schema::dropIfExists('widget_pages');
    }
};