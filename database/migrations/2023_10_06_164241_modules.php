<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up():void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('name_label')->nullable();
            $table->string('name_placeholder')->nullable();
            $table->string('term')->nullable();
            $table->string('slug')->nullable();
            $table->tinyInteger('is_slug')->default(0);
            $table->tinyInteger('is_menu')->default(0);
            $table->tinyInteger('is_description')->default(0);
            $table->string('description_label')->nullable();
            $table->string('description_placeholder')->nullable();
            $table->tinyInteger('is_highlights')->default(0);
            $table->string('highlights_label')->nullable();
            $table->string('highlights_placeholder')->nullable();
            $table->tinyInteger('is_image')->default(0);
            $table->string('multi_images')->nullable();
            $table->string('images_label')->nullable();
            $table->tinyInteger('is_seo')->default(0);
            $table->tinyInteger('category')->default(0);
            $table->string('category_label')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('category_module_id')->nullable();
            $table->tinyInteger('multiple_category')->default(0);
            $table->string('sub_category')->nullable();
            $table->string('sub_category_label')->nullable();
            $table->integer('parent_sub_id')->nullable();
            $table->tinyInteger('tags')->default(0);
            $table->string('tags_label')->nullable();
            $table->string('thumbnail_height')->nullable();
            $table->string('thumbnail_width')->nullable();
            $table->string('extra_fields')->nullable();
            $table->enum('status', ['active', 'blocked'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down():void
    {
        Schema::dropIfExists('modules');
    }
};
