<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddIpAddressColumn extends Migration {

    public function up()
    {
        Schema::table('reports', function(Blueprint $table) {
            $table->string('ip')->nullable();
        });
    }

    public function down()
    {
        Schema::table('reports', function(Blueprint $table) {
            $table->dropColumn('ip');
        });
    }
}