<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableLogErrors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('log_errors', function (Blueprint $table) {
            $table->id();
            $table->string('school');
            $table->string('page')->nullable();
            $table->string('error')->nullable();
            $table->string('message')->nullable();
            $table->dateTime('date')->nullable();
            $table->text('handled')->nullable();
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
