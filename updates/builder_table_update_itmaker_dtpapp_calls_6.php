<?php namespace Itmaker\DtpApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateItmakerDtpappCalls6 extends Migration
{
    public function up()
    {
        Schema::table('itmaker_dtpapp_calls', function($table)
        {
            $table->double('employee_lat', 16, 10)->nullable();
            $table->double('employee_long', 16, 10)->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('itmaker_dtpapp_calls', function($table)
        {
            $table->dropColumn('employee_lat');
            $table->dropColumn('employee_long');
        });
    }
}
