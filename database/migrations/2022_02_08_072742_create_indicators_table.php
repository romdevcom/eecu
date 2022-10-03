<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicators', function (Blueprint $table) {
	        $table->id();
	        $table->string('name', 250);
	        $table->string('name_en', 250)->nullable()->default(null);
	        $table->string('code', 25)->nullable()->default(null);
	        $table->string('formula', 100)->nullable()->default(null);
	        $table->string('dimension', 25)->nullable()->default(null);
	        $table->string('dimension_en', 50)->nullable()->default(null);
	        $table->tinyInteger('precision')->nullable()->default(null);
	        $table->bigInteger('numerator_id')->unsigned();
	        $table->bigInteger('denominator_id')->unsigned();
	        $table->bigInteger('sector_id')->unsigned();
	        $table->bigInteger('group_id')->unsigned();
	        $table->float('weight')->nullable()->default(null);
	        $table->string('status')->nullable()->default('draft');
	        $table->integer('order')->nullable()->default(999);
	        $table->timestamps();
        });

	    Schema::table('indicators', function (Blueprint $table) {
		    $table->foreign('numerator_id')->references('id')->on('measures');
	    });

	    Schema::table('indicators', function (Blueprint $table) {
		    $table->foreign('denominator_id')->references('id')->on('measures');
	    });

	    Schema::table('indicators', function (Blueprint $table) {
		    $table->foreign('sector_id')->references('id')->on('sectors');
	    });

	    Schema::table('indicators', function (Blueprint $table) {
		    $table->foreign('group_id')->references('id')->on('groups');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('indicators');
    }
}
