<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\TaskSubmission;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        // 1. Core Statistics
        $totalStudents = User::where('role', 'student')->where('is_active', true)->count();
        $pendingLeaves = LeaveRequest::where('status', 'pending')->count();
        $activeTasks = Task::count();
        
        // 2. Attendance Snapshot for Today
        $presentToday = Attendance::where('attendance_date', now()->toDateString())->count();
        $absentToday = max(0, $totalStudents - $presentToday);

        // 3. Recent Activity (Latest 5 submissions)
        $recentSubmissions = TaskSubmission::with(['user', 'task'])
                                ->latest()
                                ->take(5)
                                ->get();

        return view('teacher.dashboard', compact(
            'totalStudents', 
            'pendingLeaves', 
            'activeTasks', 
            'presentToday', 
            'absentToday',
            'recentSubmissions'
        ));
    }
}