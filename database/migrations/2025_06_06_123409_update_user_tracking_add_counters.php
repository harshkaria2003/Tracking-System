<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
{
    Schema::table('user_tracking', function (Blueprint $table) {
        $table->integer('click_count')->default(0);
        $table->integer('scroll_count')->default(0);
        $table->integer('keypress_count')->default(0);
    });
}


    public function down(): void
    {
        
    }
};
