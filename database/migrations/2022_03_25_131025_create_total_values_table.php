<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTotalValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('total_values', function (Blueprint $table) {
	        $table->id();
	        $table->bigInteger('year_id')->unsigned();
	        $table->bigInteger('community_id')->unsigned();
	        $table->bigInteger('sector_id')->nullable()->default(null);
	        $table->bigInteger('group_id')->nullable()->default(null);
	        $table->string('value', 30);
	        $table->string('type', 30);
	        $table->string('status', 30)->nullable()->default('waiting');
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
        Schema::dropIfExists('total_values');
    }
}
