<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReportsTable extends Migration {

	public function up()
	{
		Schema::create('reports', function(Blueprint $table) {
			$table->increments('pkey');
			$table->string('id', 255)->unique();
			$table->text('comment')->nullable();
			$table->text('youtubeUrl')->nullable();
			$table->string('displayName', 255)->nullable();
			$table->string('profilePictureUrl', 255)->nullable();
			$table->tinyInteger('approvalStatus')->nullable()->default('0');
			$table->integer('rep');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('reports');
	}
}