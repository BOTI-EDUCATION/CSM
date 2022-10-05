<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTablesQuotations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('niveaux')->nullable();
            $table->string('nombre')->nullable();
            $table->string('pack')->nullable();
            $table->string('ville')->nullable();
            $table->string('tel')->nullable();
            $table->string('email')->nullable();
            $table->string('nom')->nullable();
            $table->string('ecole')->nullable();
            $table->string('traitement')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('quotation_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quotation_id')->nullable();
            $table->string('titre')->nullable();
            $table->string('file')->nullable();
            $table->foreign('quotation_id')->references('id')->on('quotations');
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
