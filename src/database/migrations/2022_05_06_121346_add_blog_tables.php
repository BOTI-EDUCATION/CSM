<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBlogTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullable();
            $table->string('alias')->nullable();
            $table->text('details')->nullable();
            $table->string('image')->nullable();
            $table->boolean('enabled')->nullable();
            $table->integer('views')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('posts_vues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id')->nullable();
            $table->string('client')->nullable();
            $table->string('source')->nullable();
            $table->text('device')->nullable();
            $table->string('navigateur')->nullable();
            $table->foreign('post_id')->references('id')->on('posts');
            $table->softDeletes();
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
