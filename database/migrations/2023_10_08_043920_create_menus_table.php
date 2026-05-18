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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->bigInteger('menu_type_id')->unsigned()->nullable();
            $table->bigInteger('post_id')->unsigned()->nullable();
            $table->integer('order')->nullable();
            $table->integer('parent_id')->nullable();
            $table->enum('status', ['active', 'blocked'])->default('active');
            $table->enum('menu_is', ['external', 'internal'])->default('internal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
