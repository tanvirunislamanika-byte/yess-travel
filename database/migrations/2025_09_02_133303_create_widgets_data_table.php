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
        Schema::create('widgets_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('widget_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('widgets_data');
    }
};