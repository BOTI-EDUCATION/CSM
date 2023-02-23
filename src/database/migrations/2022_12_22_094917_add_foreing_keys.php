<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeingKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('releases',function(Blueprint $table){
            $table->unsignedBigInteger('type_id')->onDelete('cascade');
            $table->foreign('type_id')->references("id")->on('types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('releases',function(Blueprint $table){
            $table->dropForeign(['type_id']);
            $table->dropColumn("type_id");
        });
    }
}
