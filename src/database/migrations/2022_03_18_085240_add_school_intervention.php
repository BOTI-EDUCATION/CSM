<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSchoolIntervention extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_interventions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_intervention_id')->nullable();
            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->string('collaborateurs')->nullable();
            $table->string('label')->nullable();
            $table->text('details')->nullable();
            $table->dateTime('date')->nullable();
            $table->string('nature')->nullable();
            $table->foreign('school_intervention_id')->references('id')->on('users');
            $table->foreign('responsable_id')->references('id')->on('users');
            $table->softDeletes();
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
