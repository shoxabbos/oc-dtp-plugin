<?php namespace Itmaker\DtpApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateItmakerDtpappStatuses extends Migration
{
    public function up()
    {
        Schema::table('itmaker_dtpapp_statuses', function($table)
        {
            $table->integer('sort_order')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('itmaker_dtpapp_statuses', function($table)
        {
            $table->dropColumn('sort_order');
        });
    }
}
