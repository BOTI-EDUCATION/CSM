<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSchools extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->string('social_links')->nullable()->after('presentation');
            $table->string('localisation')->nullable()->after('adresse');
            $table->string('start_year')->nullable()->after('localisation');
            $table->string('first_year_boti')->nullable()->after('start_year');
            $table->string('banner')->nullable()->after('logo');
            $table->string('pics')->nullable()->after('banner');
            $table->string('files')->nullable()->after('pics');
            $table->string('effectif_maternelle')->nullable();
            $table->string('effectif_primaire')->nullable();
            $table->string('effectif_college')->nullable();
            $table->string('effectif_lycee')->nullable();
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
