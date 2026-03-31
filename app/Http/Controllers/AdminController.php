<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use App\Services\WhatsAppService;

class AdminController extends Controller
{
    /**
     * Display the main Oversight Hub.
     */
    public function index()
    {
        // 1. Global System Stats
        $stats = [
            'total_users' => User::count(),
            'pending_approvals' => User::where('is_active', false)->count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_students' => User::where('role', 'student')->count(),
        ];

        // 2. System Health (Today)
        $todayStats = [
            'attendance_count' => Attendance::where('attendance_date', now()->toDateString())->count(),
            'pending_leaves' => LeaveRequest::where('status', 'pending')->count(),
            'whatsapp_online' => WhatsAppService::healthCheck(),
        ];

        // 3. Top Performing Students (Based on Attendance count)
        $topStudents = User::where('role', 'student')
            ->where('is_active', true)
            ->withCount('attendances')
            ->orderBy('attendances_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.summary', compact('stats', 'todayStats', 'topStudents'));
    }

    /**
     * Global Staff Directory: View all Students, Teachers, and HR.
     */
    public function userList()
    {
        // Fetch all users except the currently logged-in Admin, 
        // categorized by role for the Global Directory view.
        $users = User::where('id', '!=', auth()->id())
                     ->orderBy('role')
                     ->orderBy('name')
                     ->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * High-level system settings (Optional).
     */
    public function systemSettings()
    {
        return view('admin.settings');
    }
}