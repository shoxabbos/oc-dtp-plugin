<?php namespace Itmaker\DtpApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration1017 extends Migration
{
    public function up()
    {
        Schema::table('users', function($table)
        {
            $table->string('type')->default('client');
        });
    }

    public function down()
    {
        Schema::table('users', function($table)
        {
            $table->dropColumn('type');
        });
    }
}