<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeFieldsOnTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('school_tickets',function(Blueprint $table){
            $table->string('dure');
            $table->string('hour');
            $table->string('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('school_tickets',function(Blueprint $table){
            $table->dropColumn('dure');
            $table->dropColumn('hour');
            $table->dropColumn('status');
        });
    }
}
