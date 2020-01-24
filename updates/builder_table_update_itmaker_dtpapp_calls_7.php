<?php namespace Itmaker\DtpApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateItmakerDtpappCalls7 extends Migration
{
    public function up()
    {
        Schema::table('itmaker_dtpapp_calls', function($table)
        {
            $table->boolean('can_set_arrived_status')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('itmaker_dtpapp_calls', function($table)
        {
            $table->dropColumn('can_set_arrived_status');
        });
    }
}
