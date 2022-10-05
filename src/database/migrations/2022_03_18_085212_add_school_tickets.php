<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSchoolTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->string('collaborateurs')->nullable();
            $table->string('label')->nullable();
            $table->text('details')->nullable();
            $table->dateTime('date')->nullable();
            $table->string('channel')->nullable();
            $table->string('nature')->nullable();
            $table->string('genre')->nullable();
            $table->string('priority')->nullable();
            $table->string('files')->nullable();
            $table->string('state')->nullable();
            $table->text('state_history')->nullable();
            $table->text('edit_history')->nullable();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->foreign('contact_id')->references('id')->on('school_contacts');
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
