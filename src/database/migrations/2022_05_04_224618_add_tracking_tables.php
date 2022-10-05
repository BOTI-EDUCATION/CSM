<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrackingTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracking_sections', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('alias')->unique();
            $table->string('icone')->nullable();
            $table->text('edit_history')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('tracking_criterias', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('alias')->unique();
            $table->string('icone')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->text('edit_history')->nullable();
            $table->foreign('section_id')->references('id')->on('tracking_sections');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('tracking_metrics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->nullable();
            $table->unsignedBigInteger('criteria_id')->nullable();
            $table->string('value')->nullable();
            $table->text('meta')->nullable();
            $table->foreign('school_id')->references('id')->on('schools');
            $table->foreign('criteria_id')->references('id')->on('tracking_criterias');
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::table('schools', function (Blueprint $table) {
            $table->double('tracking_rank')->nullable()->after('link');
            $table->text('evaluations')->nullable()->after('tracking_rank');
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
