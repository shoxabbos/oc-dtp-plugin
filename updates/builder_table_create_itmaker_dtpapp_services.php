<?php namespace Itmaker\DtpApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateItmakerDtpappServices extends Migration
{
    public function up()
    {
        Schema::create('itmaker_dtpapp_services', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('employe_group_code');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('itmaker_dtpapp_services');
    }
}
