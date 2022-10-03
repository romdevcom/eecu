<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
	        $table->id();
	        $table->string('name');
	        $table->text('description')->nullable()->default(null);
	        $table->string('name_en')->nullable()->default(null);
	        $table->text('description_en')->nullable()->default(null);
	        $table->string('code')->nullable()->default(null);
	        $table->string('icon')->nullable()->default(null);
	        $table->boolean('used_in_calculations')->nullable()->default(false);
	        $table->tinyInteger('formula')->nullable()->default(null);
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
        Schema::dropIfExists('groups');
    }
}
