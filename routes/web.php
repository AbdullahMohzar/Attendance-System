<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\HRController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AdminController; 
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Services\WhatsAppService;

// --- Public Landing Page ---
Route::get('/', function () {
    return view('welcome');
});

// --- SMART DASHBOARD REDIRECTOR ---
Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'admin') return redirect()->route('admin.summary');
    if ($user->role === 'hr') return redirect()->route('hr.pending');
    if ($user->role === 'teacher') return redirect()->route('teacher.dashboard');

    // Student Logic
    $userId = $user->id;
    $attendances = Attendance::where('user_id', $userId)->orderBy('attendance_date', 'desc')->get();
    $leaveRequests = LeaveRequest::where('user_id', $userId)->orderBy('leave_date', 'desc')->get();
    $hasMarkedToday = Attendance::where('user_id', $userId)->where('attendance_date', now()->toDateString())->exists();

    $startDate = now()->startOfMonth();
    $endDate = now();
    $workdaysCount = 0;
    for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
        if ($date->isWeekday()) { $workdaysCount++; }
    }

    $presentCount = $attendances->where('attendance_date', '>=', $startDate->toDateString())->count();
    $percentage = ($workdaysCount > 0) ? ($presentCount / $workdaysCount) * 100 : 0;

    if ($percentage >= 90) { $grade = 'A'; $color = 'text-green-600'; }
    elseif ($percentage >= 75) { $grade = 'B'; $color = 'text-blue-600'; }
    elseif ($percentage >= 60) { $grade = 'C'; $color = 'text-orange-600'; }
    else { $grade = 'D'; $color = 'text-red-600'; }

    return view('dashboard', compact('hasMarkedToday', 'attendances', 'leaveRequests', 'grade', 'percentage', 'color', 'workdaysCount'));
})->middleware(['auth'])->name('dashboard');

// --- ADMIN ONLY: GLOBAL OVERSIGHT & STAFF MANAGEMENT ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/summary', [AdminController::class, 'index'])->name('admin.summary');
    
    // Global User List (Students, Teachers, HR)
    Route::get('/users', [AdminController::class, 'userList'])->name('admin.users.index');
});

// --- HR & ADMIN: USER APPROVALS ---
Route::middleware(['auth', 'role:hr,admin'])->prefix('hr')->group(function () {
    Route::get('/pending', [HRController::class, 'index'])->name('hr.pending');
    Route::post('/approve/{id}', [HRController::class, 'approve'])->name('hr.approve');
    
    // Shared Student-only view
    Route::get('/students', [HRController::class, 'studentList'])->name('admin.students');
    Route::delete('/users/{id}', [AttendanceController::class, 'destroyUser'])->name('admin.students.destroy');
});

// --- TEACHER ONLY: TASK MANAGEMENT ---
// Note: Admin removed from here to restrict assignment power
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('admin.tasks');
    Route::post('/tasks/store', [TaskController::class, 'store'])->name('admin.tasks.store');
    Route::get('/tasks/submissions', [TaskController::class, 'viewSubmissions'])->name('admin.tasks.submissions');
    Route::post('/submissions/{id}/review', [TaskController::class, 'reviewSubmission'])->name('admin.tasks.review');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('admin.tasks.destroy');
});

// --- SHARED MANAGEMENT: LEAVES & REPORTS ---
Route::middleware(['auth', 'role:teacher,admin'])->prefix('management')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'index'])->name('teacher.dashboard');

    // Leave Management
    Route::get('/leaves', function () {
        $allLeaves = LeaveRequest::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.leaves', compact('allLeaves'));
    })->name('admin.leaves');
    Route::post('/leaves/{id}/status', [LeaveController::class, 'updateStatus'])->name('admin.leaves.status');

    // Attendance Reports
    Route::get('/reports', [AttendanceController::class, 'reportIndex'])->name('admin.reports');
    Route::get('/reports/generate', [AttendanceController::class, 'generateReport'])->name('admin.reports.generate');
    
    // Logs & Record Deletion
    Route::get('/students/{id}/attendance', [AttendanceController::class, 'manageUserAttendance'])->name('admin.students.attendance');
    Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
});

// --- STUDENT ACTIONS ---
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::post('/leave/store', [LeaveController::class, 'store'])->name('leave.store');
    Route::get('/student/tasks', [TaskController::class, 'studentIndex'])->name('tasks.index');
    Route::post('/student/tasks/{id}/submit', [TaskController::class, 'submitTask'])->name('tasks.submit');
});

// --- SHARED PROFILE ROUTES ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- SYSTEM HEALTH CHECK ---
Route::get('/test-whatsapp', function () {
    $status = WhatsAppService::healthCheck();
    return response()->json(['online' => $status]);
});

require __DIR__.'/auth.php';