<?php

use Illuminate\Database\Migrations\Migration;

class AddIpAddressToReports extends Migration {

    public function up()
    {
        Schema::table('reports', function($table) {
            $table->string('ip')->nullable();
        });
    }

    public function down()
    {
        Schema::table('reports', function($table) {
            $table->dropColumn('ip');
        });
    }

}
