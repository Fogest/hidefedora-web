<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
class CreateReportsTable extends Migration {
    public function up()
    {
        Schema::create('reports', function(Blueprint $table) {
            $table->increments('id');
            $table->string('profileId', 255)->unique();
            $table->text('comment')->nullable();
            $table->text('youtubeUrl')->nullable();
            $table->string('displayName', 255)->nullable();
            $table->string('profilePictureUrl', 255)->nullable();
            $table->tinyInteger('approvalStatus')->nullable()->default('0');
            $table->integer('rep')->default('1');
            $table->timestamps();
            $table->string('approvingUser', 255)->nullable()->default(NULL);
            $table->string('ip')->nullable();
        });
    }
    public function down()
    {
        Schema::drop('reports');
    }
}