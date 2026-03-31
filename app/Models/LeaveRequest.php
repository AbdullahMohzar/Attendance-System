<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    // Add this line!
    protected $fillable = ['user_id', 'leave_date', 'reason', 'status', 'admin_comment'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}