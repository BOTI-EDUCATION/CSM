<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableSchoolChecklist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_checklist', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('checklist_item_id');
            $table->foreign('checklist_item_id')->references('id')->on('checklist_items');
            $table->unsignedBigInteger('school_id');
            $table->foreign('school_id')->references('id')->on('schools');
            $table->string('done')->nullable();
            $table->string('comment')->nullable();
            $table->text('edit_history')->nullable();
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
