<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 255);
			$table->string('password', 255);
			$table->integer('user_level')->nullable()->default('0');
			$table->string('email', 255)->unique();
			$table->string('account_creation_ip', 255)->nullable();
			$table->timestamps();
			$table->rememberToken('rememberToken');
		});
	}

	public function down()
	{
		Schema::drop('users');
	}
}