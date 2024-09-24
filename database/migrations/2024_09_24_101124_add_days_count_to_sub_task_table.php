<?php

use App\Models\SubTask;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(!Schema::hasColumn('sub_tasks', 'days_count')){
            Schema::table('sub_tasks', function (Blueprint $table) {
                $table->integer('days_count')->after('start_date')->nullable();
            });
        }
        $sub_tasks = SubTask::select(['id', 'start_date', 'due_date'])->whereNotNull(['due_date', 'start_date'])->get();

        foreach($sub_tasks as $sub_task){
            try{
                $start = Carbon::parse($sub_task->start_date);
                $due = Carbon::parse($sub_task->due_date);
                $sub_task->days_count = $start->diffInDays($due);
                $sub_task->saveQuietly();
            }catch(\Exception $e){}
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_tasks', function (Blueprint $table) {
            $table->dropColumn('days_count');
        });
    }
};
