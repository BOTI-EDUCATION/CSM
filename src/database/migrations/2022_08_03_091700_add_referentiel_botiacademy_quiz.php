<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReferentielBotiacademyQuiz extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academy_quiz', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->string('alias')->unique()->nullable();
            $table->string('label')->nullable();
            $table->string('description')->nullable();
            $table->foreign('course_id')->references('id')->on('academy_courses');
        });

        Schema::create('academy_quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_id')->nullable();
            $table->string('alias')->unique()->nullable();
            $table->string('type')->nullable();
            $table->string('titre')->nullable();
            $table->string('description')->nullable();
            $table->integer('ordre')->nullable();
            $table->string('image')->nullable();
            $table->text('options')->nullable();
            $table->text('correct_answer')->nullable();
            $table->foreign('quiz_id')->references('id')->on('academy_quiz');
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
