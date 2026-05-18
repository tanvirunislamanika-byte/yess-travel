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
        Schema::create('fields_show_in_list', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('module_id')->nullable();
            $table->string('field')->nullable();
            $table->string('field_title')->nullable();
            $table->string('field_type')->nullable();
            $table->integer('field_attr')->nullable();
            $table->timestamps();

            // Add foreign key constraints
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fields_show_in_list');
    }
};
