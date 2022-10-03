<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMeasuresXSources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('measures_x_sources', function (Blueprint $table) {
		    $table->id();
		    $table->bigInteger('measures_id')->unsigned();
		    $table->bigInteger('sources_id')->unsigned();
		    $table->timestamps();
	    });

	    Schema::table('measures_x_sources', function (Blueprint $table) {
		    $table->foreign('measures_id')->references('id')->on('measures');
	    });

	    Schema::table('measures_x_sources', function (Blueprint $table) {
		    $table->foreign('sources_id')->references('id')->on('sources');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_measures_x_sources');
    }
}
