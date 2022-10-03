<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeasuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('measures', function (Blueprint $table) {
		    $table->id();
		    $table->string('name');
		    $table->string('name_en')->nullable()->default(null);
		    $table->string('code')->nullable()->default(null);
		    $table->string('dimension')->nullable()->default(null);
		    $table->tinyInteger('precision')->nullable()->default(null);
		    $table->bigInteger('source_id')->unsigned();
		    $table->string('status')->nullable()->default('draft');
		    $table->integer('order')->nullable()->default(999);
		    $table->timestamps();
	    });

	    Schema::table('measures', function (Blueprint $table) {
		    $table->foreign('source_id')->references('id')->on('sources');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('measures');
    }
}
