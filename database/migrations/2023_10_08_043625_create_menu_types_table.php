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
       Schema::create('menu_types', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->enum('status', ['active', 'blocked'])->default('active');
            $table->timestamps();
        });

        // Insert initial data
        DB::table('menu_types')->insert([
            [
                'title' => 'Top Header Menu',
                'slug' => 'top-header-menu',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Quick links',
                'slug' => 'quick-links',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_types');
    }
};
