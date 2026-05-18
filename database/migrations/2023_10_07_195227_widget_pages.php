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
        Schema::create('widget_pages', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->enum('status', ['active', 'blocked'])->default('active');
            $table->timestamps();
        });

        // Insert data into widget_pages table
        DB::table('widget_pages')->insert([
            [
                'id' => 1,
                'title' => 'Site Settings',
                'slug' => 'site-settings',
                'status' => 'active',
                'created_at' => '2020-10-25 20:36:54',
                'updated_at' => '2020-10-25 20:36:54',
            ],
            [
                'id' => 4,
                'title' => 'Theme Setting',
                'slug' => 'theme-setting',
                'status' => 'active',
                'created_at' => '2022-04-17 02:04:39',
                'updated_at' => '2022-04-17 02:04:39',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('widget_pages');
    }
};
