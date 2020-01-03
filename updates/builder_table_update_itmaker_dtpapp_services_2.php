<?php namespace Itmaker\DtpApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateItmakerDtpappServices2 extends Migration
{
    public function up()
    {
        Schema::table('itmaker_dtpapp_services', function($table)
        {
            $table->string('type');
            $table->dropColumn('employe_group_code');
            $table->dropColumn('parent_id');
            $table->dropColumn('sort_order');
            $table->dropColumn('nest_left');
            $table->dropColumn('nest_right');
            $table->dropColumn('nest_depth');
        });
    }
    
    public function down()
    {
        Schema::table('itmaker_dtpapp_services', function($table)
        {
            $table->dropColumn('type');
            $table->string('employe_group_code', 191);
            $table->integer('parent_id')->nullable();
            $table->integer('sort_order')->nullable();
            $table->integer('nest_left')->unsigned();
            $table->integer('nest_right')->unsigned();
            $table->integer('nest_depth')->unsigned();
        });
    }
}
