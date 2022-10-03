<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeasuresValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measures_values', function (Blueprint $table) {
            $table->id();
	        $table->bigInteger('measure_id')->unsigned();
	        $table->bigInteger('year_id')->unsigned();
	        $table->bigInteger('community_id')->unsigned();
	        $table->bigInteger('file_id')->nullable()->default(null);
	        $table->string('value', 30);
	        $table->string('status', 30)->nullable()->default('not verified');
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
        Schema::dropIfExists('measures_values');
    }
}
