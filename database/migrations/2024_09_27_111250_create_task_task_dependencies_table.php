<?php

use App\Models\Task;
use App\Models\TaskTaskDependency;
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
        if(!Schema::hasTable('task_task_dependencies')){
            Schema::create('task_task_dependencies', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->foreignIdFor(Task::class,'preceding_task_id')->index('task_users_preceding_task_id_foreign')->cascadeOnUpdate()->cascadeOnDelete();
                $table->foreignIdFor(Task::class,'dependent_task_id')->index('task_users_dependent_task_id_foreign')->cascadeOnUpdate()->cascadeOnDelete();
                $table->timestamps();
            });
        }
        DB::beginTransaction();
        $tasks = Task::select(['id', 'dependent_task_id'])->whereNotNull('dependent_task_id')->get();
        foreach($tasks as $task){
            $dependency = new TaskTaskDependency();
            $dependency->preceding_task_id = $task->dependent_task_id;
            $dependency->dependent_task_id = $task->id;
            $dependency->saveQuietly();
        }
        if(Schema::hasColumn('tasks', 'dependent_task_id')){
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropForeign(['dependent_task_id']);
                $table->dropColumn('dependent_task_id');
            });
        }
        DB::commit();

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_task_dependencies');
    }
};
