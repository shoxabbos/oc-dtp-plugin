<?php namespace Itmaker\DtpApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateItmakerDtpappCallsServices extends Migration
{
    public function up()
    {
        Schema::rename('itmaker_dtpapp_call_service', 'itmaker_dtpapp_calls_services');
    }
    
    public function down()
    {
        Schema::rename('itmaker_dtpapp_calls_services', 'itmaker_dtpapp_call_service');
    }
}
