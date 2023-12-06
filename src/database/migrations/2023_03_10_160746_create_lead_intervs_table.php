<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadIntervsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_intervs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('leads_id');
            $table->bigInteger('sales_id');
            $table->string('label');
            $table->text('details');
            $table->string('date');
            $table->string('type');
            $table->string('nature');
            $table->string('channel');
            $table->string('state');
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
        Schema::dropIfExists('lead_intervs');
    }
}
