<?php namespace Itmaker\DtpApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateItmakerDtpappTarifs extends Migration
{
    public function up()
    {
        Schema::table('itmaker_dtpapp_services', function($table)
        {
            $table->string('tab');
        });
    }
    
    public function down()
    {
        Schema::table('itmaker_dtpapp_services', function($table)
        {
            $table->dropColumn('tab');
        });
    }
}