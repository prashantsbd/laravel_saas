<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProjectDelay extends Model
{
    use HasFactory;
    

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'project_delay_tasks', 'delay_id', 'delayed_task_id')->using(ProjectDelayTask::class);
    }
    
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
