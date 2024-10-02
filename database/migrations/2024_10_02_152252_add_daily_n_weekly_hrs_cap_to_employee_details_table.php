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
        Schema::table('employee_details', function (Blueprint $table) {
            if(!Schema::hasColumn('employee_details', 'daily_hrs_cap')){
                $table->double('daily_hrs_cap')->after('hourly_rate')->nullable();
            }
            if(!Schema::hasColumn('employee_details', 'weekly_hrs_cap')){
                $table->double('weekly_hrs_cap')->after('daily_hrs_cap')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_details', function (Blueprint $table) {
            if(Schema::hasColumn('employee_details', 'daily_hrs_cap')){
                $table->dropColumn('daily_hrs_cap');
            }
            if(Schema::hasColumn('employee_details', 'weekly_hrs_cap')){
                $table->dropColumn('weekly_hrs_cap');
            }
        });
    }
};
