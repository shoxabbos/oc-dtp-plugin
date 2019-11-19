<?php namespace Itmaker\DtpApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateItmakerDtpappServices2 extends Migration
{
    public function up()
    {
        Schema::table('itmaker_dtpapp_services', function($table)
        {
            $table->integer('nest_left')->unsigned();
            $table->integer('nest_right')->unsigned();
            $table->integer('nest_depth')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::table('itmaker_dtpapp_services', function($table)
        {
            $table->dropColumn('nest_left');
            $table->dropColumn('nest_right');
            $table->dropColumn('nest_depth');
        });
    }
}