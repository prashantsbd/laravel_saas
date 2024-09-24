<?php

use App\Models\Task;
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
        if(!Schema::hasColumn('tasks', 'days_count')){
            Schema::table('tasks', function (Blueprint $table) {
                $table->integer('days_count')->after('start_date')->nullable();
            });
        }

        $tasks = Task::withTrashed()->select(['id', 'start_date', 'due_date'])->whereNotNull('due_date')->get();

        foreach($tasks as $task){
            try{
                $start = Carbon::parse($task->start_date);
                $due = Carbon::parse($task->due_date);
                $task->days_count = $start->diffInDays($due);
                $task->saveQuietly();
            }catch(\Exception $e){}
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('days_count');
        });
    }
};
