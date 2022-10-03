<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
	        $table->string('file', 350);
	        $table->string('name', 350);
	        $table->string('extension', 10)->nullable()->default('pdf');
	        $table->bigInteger('confidence_id')->unsigned();
	        $table->bigInteger('source_id')->unsigned();
	        $table->bigInteger('community_id')->nullable()->default(null);
	        $table->bigInteger('year_id')->nullable()->default(null);
            $table->timestamps();
        });

	    Schema::table('files', function (Blueprint $table) {
		    $table->foreign('confidence_id')->references('id')->on('confidences');
	    });

	    Schema::table('files', function (Blueprint $table) {
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
        Schema::dropIfExists('files');
    }
}
