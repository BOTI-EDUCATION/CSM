<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableTheme extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('alias')->unique();
            $table->unsignedBigInteger('user_by')->nullable();
            $table->foreign('user_by')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::create('module_themes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('module_id')->nullable();
            $table->unsignedBigInteger('theme_id')->nullable();
            $table->unsignedBigInteger('user_by')->nullable();
            $table->foreign('module_id')->references('id')->on('modules');
            $table->foreign('theme_id')->references('id')->on('themes');
            $table->foreign('user_by')->references('id')->on('users');
            $table->timestamps();
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
