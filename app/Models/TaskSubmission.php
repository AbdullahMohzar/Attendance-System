<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id', 
        'user_id', 
        'submission_text',
        'attachment', 
        'status', 
        'admin_feedback'
    ];

    // Link back to the Task
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    // Link back to the Student (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}