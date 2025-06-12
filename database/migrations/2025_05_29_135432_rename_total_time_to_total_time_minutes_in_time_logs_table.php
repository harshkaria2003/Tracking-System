<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTotalTimeToTotalTimeMinutesInTimeLogsTable extends Migration
{
    public function up()
    {
        Schema::table('time_logs', function (Blueprint $table) {
            $table->renameColumn('total_time', 'total_time_minutes');
        });
    }

    public function down()
    {
        Schema::table('time_logs', function (Blueprint $table) {
            $table->renameColumn('total_time_minutes', 'total_time');
        });
    }   
}
