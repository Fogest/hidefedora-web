<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReportsLogTable extends Migration {

	public function up()
	{
		Schema::create('reportsLog', function(Blueprint $table) {
			$table->increments('pkey');
			$table->string('ip', 255);
			$table->string('reportingId', 255);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('reportsLog');
	}
}