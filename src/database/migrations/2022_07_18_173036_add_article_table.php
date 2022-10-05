<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        // 'details',
        // 'blogs',
        // 'Image',
        // 'Files',
        // 'Comments',
        // 'Keywords',

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullable();
            $table->string('details')->nullable();
            $table->json('blogs')->nullable();
            $table->json('files')->nullable();
            $table->json('image')->nullable();
            $table->json('comments')->nullable();
            $table->json('likes')->nullable();
            $table->json('keywords')->nullable();
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
