<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    // Add this line!
    protected $fillable = [
    'user_id', 
    'start_date', 
    'end_date', 
    'reason', 
    'status'
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leaveRequests() {
        return $this->hasMany(LeaveRequest::class)->orderBy('leave_date', 'desc');
    }
}