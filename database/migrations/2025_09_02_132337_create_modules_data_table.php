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
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('auth_id')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('category')->nullable();
            $table->unsignedBigInteger('sub_category')->nullable();
            $table->string('category_ids')->nullable();
            $table->string('tag_ids')->nullable();
            
            // Extra fields 1-50
            for ($i = 1; $i <= 50; $i++) {
                $table->longText("extra_field_{$i}")->nullable();
            }
            
            $table->string('image')->nullable();
            $table->longText('images')->nullable();
            $table->longText('highlights')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('meta_description')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('status', ['active', 'blocked'])->default('active');
            $table->string('final_submit', 20)->nullable();
            $table->unsignedBigInteger('assign_to')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->text('task')->nullable();
            $table->text('cusines')->nullable();
            $table->text('services')->nullable();
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