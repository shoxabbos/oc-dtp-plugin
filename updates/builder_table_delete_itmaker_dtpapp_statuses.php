<?php namespace Itmaker\DtpApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableDeleteItmakerDtpappStatuses extends Migration
{
    public function up()
    {
        Schema::dropIfExists('itmaker_dtpapp_statuses');
    }
    
    public function down()
    {
        Schema::create('itmaker_dtpapp_statuses', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 191);
            $table->string('code', 191);
            $table->boolean('is_active')->nullable();
            $table->integer('sort_order')->nullable();
        });
    }
}
