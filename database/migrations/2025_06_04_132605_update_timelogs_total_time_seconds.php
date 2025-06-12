<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
  public function up()
{
    Schema::table('time_logs', function (Blueprint $table) {
        // Remove old column if it's varchar
        if (Schema::hasColumn('time_logs', 'total_time_minutes')) {
            $table->dropColumn('total_time_minutes');
        }

        // Add new column
        $table->unsignedInteger('total_time_seconds')->nullable()->after('end_time');
    });
}

public function down()
{
    Schema::table('time_logs', function (Blueprint $table) {
        $table->dropColumn('total_time_seconds');
        $table->string('total_time_minutes')->nullable(); // Revert if needed
    });
}

};
