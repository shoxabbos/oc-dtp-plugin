<?php namespace Itmaker\DtpApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class Migration1030 extends Migration
{
    public function up()
    {
        Schema::table('users', function($table)
        {
            $table->string('insurance_id');
        });
    }

    public function down()
    {
        Schema::table('users', function($table)
        {
            $table->dropColumn('insurance_id');
        });
    }
}