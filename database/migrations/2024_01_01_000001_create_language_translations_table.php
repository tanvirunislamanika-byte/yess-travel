<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('language_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('language_id')->constrained()->onDelete('cascade');
            $table->string('group_name');
            $table->string('key_name');
            $table->text('value');
            $table->timestamps();

            $table->unique(['language_id', 'group_name', 'key_name']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('language_translations');
    }
};
