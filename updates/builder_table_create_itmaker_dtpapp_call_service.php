<?php namespace Itmaker\DtpApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateItmakerDtpappCallService extends Migration
{
    public function up()
    {
        Schema::create('itmaker_dtpapp_call_service', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('call_id')->unsigned();
            $table->integer('service_id')->unsigned();
            $table->unique(['call_id', 'service_id'], 'call_id_service_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('itmaker_dtpapp_call_service');
    }
}