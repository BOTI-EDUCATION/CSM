<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsOnCheckupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('checkup_trackings',function(Blueprint $table){
            $table->dateTime('date')->nullable();
            $table->string('user')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('checkup_trackings',function(Blueprint $table){
            $table->dropColumn('date');
            $table->dropColumn('user');
        });
    }
}
