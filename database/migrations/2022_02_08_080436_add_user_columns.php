<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('users', function (Blueprint $table) {
		    $table->string('first_name', 100)->nullable()->default(null)->after('name');
		    $table->string('last_name', 100)->nullable()->default(null)->after('first_name');
		    $table->string('phone', 100)->nullable()->default(null)->after('last_name');
		    $table->string('position', 100)->nullable()->default(null)->after('phone');
		    $table->string('status', 100)->nullable()->default(null)->after('position');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
