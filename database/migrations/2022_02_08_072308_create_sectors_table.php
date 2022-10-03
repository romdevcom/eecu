<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sectors', function (Blueprint $table) {
	        $table->id();
	        $table->string('name', 250);
	        $table->string('name_en', 250)->nullable()->default(null);
	        $table->string('code', 25)->nullable()->default(null);
	        $table->string('formula', 100)->nullable()->default(null);
	        $table->bigInteger('group_id')->unsigned();
	        $table->string('status')->nullable()->default('draft');
	        $table->integer('order')->nullable()->default(999);
	        $table->timestamps();
        });

	    Schema::table('sectors', function (Blueprint $table) {
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
        Schema::dropIfExists('sectors');
    }
}
