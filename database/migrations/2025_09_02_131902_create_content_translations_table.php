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
        Schema::create('content_translations', function (Blueprint $table) {
            $table->id();
            $table->string('translatable_type');
            $table->unsignedBigInteger('translatable_id');
            $table->string('locale');
            $table->string('field_name');
            $table->longText('field_value');
            $table->timestamps();
            
            $table->index(['translatable_type', 'translatable_id']);
            $table->index('locale');
            $table->index('field_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_translations');
    }
};