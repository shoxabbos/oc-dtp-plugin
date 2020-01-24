<?php namespace Itmaker\DtpApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateItmakerDtpappCalls9 extends Migration
{
    public function up()
    {
        Schema::table('itmaker_dtpapp_calls', function($table)
        {
            $table->string('review_text', 255)->change();
        });
    }
    
    public function down()
    {
        Schema::table('itmaker_dtpapp_calls', function($table)
        {
            $table->string('review_text', 191)->change();
        });
    }
}
