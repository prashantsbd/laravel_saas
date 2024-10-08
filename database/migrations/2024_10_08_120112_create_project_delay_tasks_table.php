<?php

use App\Models\ProjectDelay;
use App\Models\Task;
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
        Schema::create('project_delay_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Task::class,'delayed_task_id')->index('tasks_delayed_task_id_foreign')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(ProjectDelay::class,'delay_id')->index('project_delays_delay_id_foreign')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_delay_tasks');
    }
};
