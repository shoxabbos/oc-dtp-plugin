<?php namespace Itmaker\DtpApp\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateItmakerDtpappRates extends Migration
{
    public function up()
    {
        Schema::table('itmaker_dtpapp_rates', function($table)
        {
            $table->renameColumn('rainting', 'raiting');
        });
    }
    
    public function down()
    {
        Schema::table('itmaker_dtpapp_rates', function($table)
        {
            $table->renameColumn('raiting', 'rainting');
        });
    }
}
