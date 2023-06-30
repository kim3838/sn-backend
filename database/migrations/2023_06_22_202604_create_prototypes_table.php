<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
	public function up()
	{
		Schema::create('prototypes', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->integer('type')->nullable();
            $table->integer('category')->nullable();
            $table->bigInteger('capacity')->nullable();
            $table->date('date_added')->nullable();
            $table->timestamps();
		});
	}

    /**
     * Reverse the migrations.
     */
	public function down()
	{
		Schema::drop('prototypes');
	}
};
