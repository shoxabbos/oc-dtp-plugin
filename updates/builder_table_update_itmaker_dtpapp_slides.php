<?php namespace Itmaker\DtpApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateItmakerDtpappSlides extends Migration
{
    public function up()
    {
        Schema::table('itmaker_dtpapp_slides', function($table)
        {
            $table->integer('sort_order');
        });
    }
    
    public function down()
    {
        Schema::table('itmaker_dtpapp_slides', function($table)
        {
            $table->dropColumn('sort_order');
        });
    }
}
