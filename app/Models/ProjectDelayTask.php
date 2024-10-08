<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectDelayTask extends Model
{
    use HasFactory;

    public function delay(): BelongsTo
    {
        return $this->belongsTo(ProjectDelay::class, 'delay_id');
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, '	delayed_task_id');
    }

}
