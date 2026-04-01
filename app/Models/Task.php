<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // Allow these fields to be filled by the form
    protected $fillable = ['title', 'description', 'due_date','task_attachment'];
    
    protected $casts = [
        'due_date' => 'datetime', // This converts the SQL string to a PHP Date object
    ];
    /**
     * Relationship: A Task has many Submissions (from different students)
     */
    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }
}