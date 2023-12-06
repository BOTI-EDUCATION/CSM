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
                $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
                $table->foreignId('category_id')->nullable()->references('id')->on('article_category')->onDelete('set null')->onUpdate('cascade');
                $table->string('label')->nullable();
                $table->string('introduction')->nullable();
                $table->longText('details')->nullable();
                $table->json('blogs')->nullable();
                $table->json('files')->nullable();
                $table->string('image')->nullable();
                $table->string('video')->nullable();
                $table->json('thumbnail')->nullable();
                $table->json('comments')->nullable();
                $table->unsignedBigInteger('likes')->nullable();
                $table->json('keywords')->nullable();
                $table->json('keys')->nullable();
                $table->boolean('visible')->nullable();
                $table->dateTime('date_evenement')->nullable();
                $table->dateTime('date_publication')->nullable();
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
