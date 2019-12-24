<?php namespace Itmaker\DtpApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateItmakerDtpappCalls extends Migration
{
    public function up()
    {
        Schema::create('itmaker_dtpapp_calls', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->double('coor_lat', 16, 10);
            $table->double('coor_long', 16, 10);
            $table->smallInteger('address');
            $table->integer('client_id')->unsigned();
            $table->integer('employe_id')->nullable();
            $table->string('employe_group_code');
            $table->integer('status_id')->unsigned();
            $table->text('addantial_comment');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('itmaker_dtpapp_calls');
    }
}