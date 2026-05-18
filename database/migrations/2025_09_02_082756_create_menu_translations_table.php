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
        Schema::create('menu_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->string('locale', 5);
            $table->string('title');
            $table->timestamps();
            
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->unique(['menu_id', 'locale']);
            $table->index(['locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_translations');
    }
};
