<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableSchools extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->integer('effectif')->nullable();
            $table->string('cycles')->nullable();
            $table->string('city')->nullable();
            $table->text('presentation')->nullable();
            $table->string('adresse')->nullable();
            $table->string('link')->nullable();
            $table->unsignedBigInteger('account_manager')->nullable();
            $table->foreign('account_manager')->references('id')->on('users');
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
