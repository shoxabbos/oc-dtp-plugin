<?php namespace Itmaker\DtpApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateItmakerDtpappEmployeesLocations extends Migration
{
    public function up()
    {
        Schema::create('itmaker_dtpapp_employees_locations', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->double('coor_lat', 16, 10);
            $table->double('coor_long', 16, 10);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('itmaker_dtpapp_employees_locations');
    }
}