<?php namespace Itmaker\DtpApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration1012 extends Migration
{
    public function up()
    {
        Schema::table('users', function($table)
        {
            $table->integer('balance')->nulable();
        });
    }

    public function down()
    {
        Schema::table('users', function($table)
        {
            $table->dropColumn('balance');
        });
    }
}