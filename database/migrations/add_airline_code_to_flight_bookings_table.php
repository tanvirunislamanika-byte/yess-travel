<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('flight_bookings', function (Blueprint $table) {
            $table->string('airline_code', 3)->nullable()->after('destination_code');
        });
    }

    public function down()
    {
        Schema::table('flight_bookings', function (Blueprint $table) {
            $table->dropColumn('airline_code');
        });
    }
}; 