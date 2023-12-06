<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckupIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkup_indicators', function (Blueprint $table) {
            $table->id();
            $table->string('alias');
            $table->string('label');
            $table->text('presentation')->nullable();
            $table->string('rubrique');
            $table->string('value_type')->nullable();
            $table->string('threshold')->nullable();
            $table->integer('order')->nullabele();
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
        Schema::dropIfExists('checkup_indicators');
    }
}
