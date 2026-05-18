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
        Schema::create('widget_data_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('widget_data_id')->constrained()->onDelete('cascade');
            $table->string('locale', 5);
            $table->string('field_name'); // extra_field_1, extra_field_2, etc.
            $table->text('field_value');
            $table->timestamps();
            
            $table->unique(['widget_data_id', 'locale', 'field_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('widget_data_translations');
    }
};