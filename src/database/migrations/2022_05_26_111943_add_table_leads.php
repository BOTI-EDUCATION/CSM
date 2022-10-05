<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableLeads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->string('pics')->nullable();
            $table->string('files')->nullable();
            $table->integer('effectif')->nullable();
            $table->string('cycles')->nullable();
            $table->string('city')->nullable();
            $table->text('presentation')->nullable();
            $table->string('social_links')->nullable();
            $table->string('adresse')->nullable();
            $table->string('localisation')->nullable();
            $table->string('start_year')->nullable();
            $table->dateTime('first_contact_date')->nullable();
            $table->dateTime('last_contact_date')->nullable();
            $table->string('links')->nullable();
            $table->timestamps();
            $table->string('effectif_maternelle')->nullable();
            $table->string('effectif_primaire')->nullable();
            $table->string('effectif_college')->nullable();
            $table->string('effectif_lycee')->nullable();
            $table->string('effectif_creche')->nullable();
            $table->text('pricing')->nullable();
            $table->string('current_solution')->nullable();
            $table->string('lead_status')->nullable();
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
