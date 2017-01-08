<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJiekousTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jiekous', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('url');
            $table->string('type');
            $table->string('datatype');
            $table->string('shoujihaobiaoshi');
            $table->string('neirongbiaoshi');
            $table->string('riqibiaoshi');
            $table->string('shijianbiaoshi');
            $table->string('fanhuizhibiaoshi');
            $table->string('chenggongdaima');
            $table->string('status')->default('0');
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
        Schema::dropIfExists('jiekous');
    }
}
