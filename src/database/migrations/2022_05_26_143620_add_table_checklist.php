<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableChecklist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('checklists', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('alias')->unique();
            $table->string('icon')->nullable();
            $table->string('presentation')->nullable();
            $table->boolean('required')->nullable();
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
