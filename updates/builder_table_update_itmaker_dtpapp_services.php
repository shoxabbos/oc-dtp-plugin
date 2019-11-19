<?php namespace Itmaker\DtpApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateItmakerDtpappServices extends Migration
{
    public function up()
    {
        Schema::table('itmaker_dtpapp_services', function($table)
        {
            $table->integer('parent_id')->nullable();
            $table->integer('sort_order')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('itmaker_dtpapp_services', function($table)
        {
            $table->dropColumn('parent_id');
            $table->dropColumn('sort_order');
        });
    }
}