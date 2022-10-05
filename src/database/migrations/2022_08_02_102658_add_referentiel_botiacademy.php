<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferentielBotiacademy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academy_themes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('label')->nullable();
            $table->string('alias')->unique()->nullable();
            $table->string('icon')->nullable();
            $table->integer('ordre')->nullable();
            $table->foreign('parent_id')->references('id')->on('academy_themes');
        });

        Schema::create('academy_courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('theme_id')->nullable();
            $table->string('label')->nullable();
            $table->string('alias')->unique()->nullable();
            $table->string('description')->nullable();
            $table->string('type')->nullable();
            $table->string('video')->nullable();
            $table->text('content')->nullable();
            $table->integer('ordre')->nullable();
            $table->foreign('theme_id')->references('id')->on('academy_themes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
