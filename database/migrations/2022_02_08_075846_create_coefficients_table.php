<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoefficientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coefficients', function (Blueprint $table) {
            $table->id();
	        $table->bigInteger('year_id')->unsigned();
	        $table->bigInteger('indicator_id')->unsigned();
	        $table->string('value', 200)->nullable()->default(null);
            $table->timestamps();
        });

	    Schema::table('coefficients', function (Blueprint $table) {
		    $table->foreign('year_id')->references('id')->on('years');
	    });

	    Schema::table('coefficients', function (Blueprint $table) {
		    $table->foreign('indicator_id')->references('id')->on('indicators');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coefficients');
    }
}
