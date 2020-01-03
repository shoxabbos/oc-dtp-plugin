<?php namespace Itmaker\DtpApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateItmakerDtpappCalls2 extends Migration
{
    public function up()
    {
        Schema::table('itmaker_dtpapp_calls', function($table)
        {
            $table->renameColumn('employe_group_code', 'type');
            $table->renameColumn('addantial_comment', 'comment');
        });
    }
    
    public function down()
    {
        Schema::table('itmaker_dtpapp_calls', function($table)
        {
            $table->renameColumn('type', 'employe_group_code');
            $table->renameColumn('comment', 'addantial_comment');
        });
    }
}
