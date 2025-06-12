<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTotalTimeMinutesToTotalTimeInTimeLogs extends Migration
{
    public function up()
    {
        Schema::table('time_logs', function (Blueprint $table) {
            $table->renameColumn('total_time_minutes', 'total_time');
            $table->integer('total_time')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('time_logs', function (Blueprint $table) {
            $table->renameColumn('total_time', 'total_time_minutes');
            $table->integer('total_time_minutes')->nullable()->change();
        });
    }
}
