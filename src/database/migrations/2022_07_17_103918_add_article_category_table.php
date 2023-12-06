<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArticleCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_category', function(Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('alias')->unique()->nullable();
            $table->string('icon')->nullable();
            $table->boolean('hasVideo')->default(false);
            $table->boolean('specific_date')->default(false);
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
