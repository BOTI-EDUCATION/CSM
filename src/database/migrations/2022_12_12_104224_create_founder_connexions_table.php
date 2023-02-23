<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFounderConnexionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('founder_connexions', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('school_alias');
            $table->string('user_agent');
            $table->string('ip');
            $table->string('device');
            $table->string('navigateur');
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
        Schema::dropIfExists('founder_connexions');
    }
}
