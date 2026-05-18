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
        Schema::create('modules_data', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->unsignedBigInteger('module_id');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->unsignedBigInteger('auth_id');
            $table->foreign('auth_id')->references('id')->on('users')->onDelete('cascade');
            $table->longText('description')->nullable();
            $table->integer('category')->nullable();
            $table->integer('sub_category')->nullable();
            $table->string('category_ids')->nullable();
            $table->string('tag_ids')->nullable();
            $table->string('image')->nullable();
            $table->longText('images')->nullable();
            $table->longText('highlights')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('meta_description')->nullable();
            $table->enum('status', ['active', 'blocked'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules_data');
    }
};
