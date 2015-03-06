<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppealsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('appeals', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('profileId', 255)->unique();
            $table->text('comment');
            $table->string('email', 255)->nullable();
            $table->tinyInteger('status')->nullable()->default('0');
            $table->text('response')->nullable();
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
		Schema::drop('appeals');
	}

}
