<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToTotalValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('total_values', function (Blueprint $table) {
	        $table->string('year_name')->after('year_id')->nullable()->default(null);
	        $table->integer('count_all')->after('type')->nullable()->default(null);
	        $table->integer('count_values')->after('count_all')->nullable()->default(null);
	        $table->integer('count_approved_values')->after('count_values')->nullable()->default(null);
	        $table->integer('percent_values')->after('count_approved_values')->nullable()->default(null);
	        $table->integer('percent_approved_values')->after('percent_values')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('total_values', function (Blueprint $table) {
            //
        });
    }
}
