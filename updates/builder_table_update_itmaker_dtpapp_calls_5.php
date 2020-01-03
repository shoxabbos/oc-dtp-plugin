<?php namespace Itmaker\DtpApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateItmakerDtpappCalls5 extends Migration
{
    public function up()
    {
        Schema::table('itmaker_dtpapp_calls', function($table)
        {
            $table->string('status', 10)->nullable(false)->unsigned(false)->default('new')->change();
        });
    }
    
    public function down()
    {
        Schema::table('itmaker_dtpapp_calls', function($table)
        {
            $table->integer('status')->nullable(false)->unsigned()->default(null)->change();
        });
    }
}
