<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJobOffer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        //Create jobOffer Table
        Schema::create('job_offer', function (Blueprint $table) {
        
            $table->id();
            $table->string('reference')->nullable();
            //$table->string('reference')->unique();
            $table->string('title');
            $table->string('introduction')->nullable();
            $table->string('details')->nullable();
            //$table->json('images')->nullable();
            //$table->json('files')->nullable();
            $table->string('infos')->nullable();
            //$table->json('keywords')->nullable();
            //$table->integer('ecole_id');
            $table->string('ecole_name')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('localization')->nullable();
           // $table->string('localization_zone')->nullable();
            $table->string('date')->nullable();
           // $table->json('validation')->nullable();
            //$table->json('hide')->nullable();
            //$table->json('edit_history')->nullable();
            //$table->json('candidates')->nullable();
        });

        

        //Create jobCandidate Table
        Schema::create('job_candidates', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->string('prenom');
            $table->string('cin')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            //$table->string('city')->nullable();
            $table->string('nationality')->nullable();
            $table->string('password')->nullable();
            $table->string('confirmedPassword')->nullable();
            
            //$table->text('address')->nullable();
            //$table->string('profile');
            //$table->date('inscription_date')->nullable();
            //$table->date('last_connection_date')->nullable();
            //$table->json('job_offers')->nullable();
        });
        
        //Create jobOfferCandidacies Table
        Schema::create('job_offer_candidacies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_offer')->nullable();
            $table->unsignedBigInteger('job_candidate')->nullable();
            $table->foreign('job_offer')->references('id')->on('job_offer');
            $table->foreign('job_candidate')->references('id')->on('job_candidates');
            $table->date('date_candidacy')->nullable();
            $table->integer('views')->nullable();
            $table->json('sent_to_client')->nullable();
            $table->json('files')->nullable();
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
