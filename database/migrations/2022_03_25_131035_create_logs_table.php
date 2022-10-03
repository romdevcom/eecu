<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
	        $table->id();
	        $table->string('event_type', 150);
	        $table->string('obj', 150);
	        $table->string('action_needed', 150);
	        $table->string('action_done', 150);
	        $table->string('user_id', 30);
	        $table->string('status', 30)->nullable()->default('not verified');
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
        Schema::dropIfExists('logs');
    }
}
