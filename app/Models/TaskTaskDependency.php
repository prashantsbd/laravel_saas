<?php

namespace App\Models;

use Froiden\RestAPI\ExtendedRelations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TaskTaskDependency extends Pivot
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'task_task_dependencies';

    public function precedingTask(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'preceding_task_id');
    }

    public function dependentTask(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'dependent_task_id');
    }
}
