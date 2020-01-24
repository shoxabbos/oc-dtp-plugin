<?php namespace Itmaker\DtpApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateItmakerDtpappCalls8 extends Migration
{
    public function up()
    {
        Schema::table('itmaker_dtpapp_calls', function($table)
        {
            $table->integer('review_star')->nullable();
            $table->string('review_text')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('itmaker_dtpapp_calls', function($table)
        {
            $table->dropColumn('review_star');
            $table->dropColumn('review_text');
        });
    }
}
