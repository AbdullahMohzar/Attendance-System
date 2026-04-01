<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            // 1. Add columns as nullable first to avoid "0000-00-00" errors
            $table->date('start_date')->nullable()->after('user_id');
            $table->date('end_date')->nullable()->after('start_date');
            
            // 2. Drop the old column if it exists
            if (Schema::hasColumn('leave_requests', 'leave_date')) {
                $table->dropColumn('leave_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
