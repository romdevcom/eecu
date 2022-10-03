<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYearValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('year_values', function (Blueprint $table) {
	        $table->id();
	        $table->bigInteger('year_id')->unsigned();
	        $table->bigInteger('community_id')->unsigned();
	        $table->string('year_slug', 30)->nullable()->default(null);
	        $table->integer('count_all')->nullable()->default(null);
	        $table->integer('count_values')->nullable()->default(null);
	        $table->integer('count_approved_values')->nullable()->default(null);
	        $table->integer('percent_values')->nullable()->default(null);
	        $table->integer('percent_approved_values')->nullable()->default(null);
	        $table->integer('points')->nullable()->default(null);
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
        Schema::dropIfExists('year_values');
    }
}
