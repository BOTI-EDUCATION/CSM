<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTrackingFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('checkup_trackings', function(Blueprint $table){
            $table->date('first_day_week')->nullable();
            $table->date('last_day_week')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('checkup_trackings', function(Blueprint $table){
            $table->dropColumn('first_day_week');
            $table->dropColumn('last_day_week');
        });
    }
}
