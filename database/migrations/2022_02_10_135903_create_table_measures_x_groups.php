<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMeasuresXGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('measures_x_groups', function (Blueprint $table) {
		    $table->id();
		    $table->bigInteger('measures_id')->unsigned();
		    $table->bigInteger('groups_id')->unsigned();
		    $table->timestamps();
	    });

	    Schema::table('measures_x_groups', function (Blueprint $table) {
		    $table->foreign('measures_id')->references('id')->on('measures');
	    });

	    Schema::table('measures_x_groups', function (Blueprint $table) {
		    $table->foreign('groups_id')->references('id')->on('groups');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('measures_x_groups');
    }
}
