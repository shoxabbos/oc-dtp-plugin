<?php namespace Itmaker\DtpApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableDeleteItmakerDtpappRates extends Migration
{
    public function up()
    {
        Schema::dropIfExists('itmaker_dtpapp_rates');
    }
    
    public function down()
    {
        Schema::create('itmaker_dtpapp_rates', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('employe_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->smallInteger('raiting')->nullable();
            $table->text('comment');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
}
