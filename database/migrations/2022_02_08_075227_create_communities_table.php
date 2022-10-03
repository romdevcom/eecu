<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communities', function (Blueprint $table) {
	        $table->id();
	        $table->string('name', 250);
	        $table->string('name_en', 250)->nullable()->default(null);
	        $table->string('chief', 200)->nullable()->default(null);
	        $table->string('contact_person', 200)->nullable()->default(null);
	        $table->string('phone', 25)->nullable()->default(null);
	        $table->string('email', 50)->nullable()->default(null);
	        $table->string('lat', 30)->nullable()->default(null);
	        $table->string('lng', 30)->nullable()->default(null);
	        $table->string('picture', 250)->nullable()->default(null);
	        $table->boolean('eea_member')->nullable()->default(false);
	        $table->string('eea_year', 25)->nullable()->default(null);
	        $table->string('eea_value', 200)->nullable()->default(null);
	        $table->boolean('eea_status')->nullable()->default(false);
	        $table->string('status')->nullable()->default('draft');
	        $table->integer('order')->nullable()->default(999);
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
        Schema::dropIfExists('communities');
    }
}
