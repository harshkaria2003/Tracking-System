<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTotalTimeMinutesColumnTypeInTimeLogsTable extends Migration
{
    public function up()
    {
        Schema::table('time_logs', function (Blueprint $table) {
            // Change column from integer to string (VARCHAR)
            $table->string('total_time_minutes', 10)->change();
        });
    }

    public function down()
    {
        Schema::table('time_logs', function (Blueprint $table) {
            // Rollback: convert it back to integer
            $table->integer('total_time_minutes')->change();
        });
    }
}
