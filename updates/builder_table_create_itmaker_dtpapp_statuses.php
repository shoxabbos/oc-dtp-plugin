<?php namespace Itmaker\DtpApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateItmakerDtpappStatuses extends Migration
{
    public function up()
    {
        Schema::create('itmaker_dtpapp_statuses', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('code');
            $table->boolean('is_active')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('itmaker_dtpapp_statuses');
    }
}
