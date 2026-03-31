<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Services\WhatsAppService;

class AttendanceController extends Controller
{
    /**
     * Student: Self-mark attendance for today.
     */
    public function store(Request $request)
    {
        $userId = auth()->id();
        $user = auth()->user();
        $today = now()->toDateString();

        $alreadyMarked = Attendance::where('user_id', $userId)
                                    ->where('attendance_date', $today)
                                    ->exists();

        if ($alreadyMarked) {
            return redirect()->back()->with('error', 'You have already marked your attendance for today!');
        }

        Attendance::create([
            'user_id' => $userId,
            'attendance_date' => $today,
            'check_in_time' => now()->toTimeString(),
        ]);

        // WhatsApp Notification
        if ($user->phone) {
            WhatsAppService::send(
                $user->phone, 
                "✅ *Attendance Recorded*\n\nHello {$user->name}, your attendance for today (" . now()->format('M d, Y') . ") has been marked at " . now()->format('h:i A') . "."
            );
        }

        return redirect()->back()->with('success', 'Attendance marked successfully.');
    }

    /**
     * Teacher/Admin: View specific logs for a student.
     */
    public function manageUserAttendance($id)
    {
        $student = User::with(['attendances' => function($q) {
            $q->orderBy('attendance_date', 'desc');
        }])->findOrFail($id);

        return view('admin.students.attendance', compact('student'));
    }

    /**
     * Teacher/Admin: Manually add attendance for a student (Backdating).
     */
    public function manualStore(Request $request, $id)
    {
        $request->validate([
            'attendance_date' => 'required|date|before_or_equal:today',
        ]);

        $exists = Attendance::where('user_id', $id)
                            ->where('attendance_date', $request->attendance_date)
                            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Attendance already exists for this date.');
        }

        Attendance::create([
            'user_id' => $id,
            'attendance_date' => $request->attendance_date,
            'check_in_time' => '09:00:00', // Default manual time
        ]);

        return redirect()->back()->with('success', 'Attendance record added manually.');
    }

    /**
     * Teacher/Admin: Delete a specific attendance record.
     */
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->back()->with('success', 'Attendance record deleted successfully.');
    }

    /**
     * HR/Admin: Completely remove a student account.
     */
    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $userName = $user->name;
        
        // This will also delete related attendances if you have cascade on delete in DB
        $user->delete();

        return redirect()->route('admin.students')->with('success', "Account for $userName has been permanently removed.");
    }

    /**
     * Teacher: Show report generation form.
     */
    public function reportIndex()
    {
        $students = User::where('role', 'student')->where('is_active', true)->get();
        return view('admin.reports.index', compact('students'));
    }

    /**
     * Teacher: Generate filtered performance report.
     */
    public function generateReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date', 
        ]);

        $start = Carbon::parse($request->start_date);
        $requestedEnd = Carbon::parse($request->end_date);
        $today = Carbon::today();

        if ($start->isFuture()) {
            return redirect()->back()->with('error', 'You cannot generate reports for future dates!');
        }

        $actualEnd = $requestedEnd->isFuture() ? $today : $requestedEnd;

        // Weekday count logic (Mon-Fri)
        $workdaysCount = 0;
        $tempDate = $start->copy();
        while ($tempDate->lte($actualEnd)) {
            if ($tempDate->isWeekday()) { $workdaysCount++; }
            $tempDate->addDay();
        }

        $query = User::where('role', 'student')->where('is_active', true);
        if ($request->student_id) { $query->where('id', $request->student_id); }

        $students = $query->with(['attendances' => function($q) use ($request) {
            $q->whereBetween('attendance_date', [$request->start_date, $request->end_date]);
        }, 'leaveRequests' => function($q) use ($request) {
            $q->where('status', 'approved')
              ->whereBetween('leave_date', [$request->start_date, $request->end_date]);
        }])->get();

        $reportData = $students->map(function($student) use ($workdaysCount) {
            $present = $student->attendances->count();
            $leaves = $student->leaveRequests->count();
            $absent = max(0, $workdaysCount - ($present + $leaves));

            $percentage = ($workdaysCount > 0) ? ($present / $workdaysCount) * 100 : 0;

            if ($percentage >= 90) { $grade = 'A'; $color = 'text-green-600'; }
            elseif ($percentage >= 75) { $grade = 'B'; $color = 'text-blue-600'; }
            elseif ($percentage >= 60) { $grade = 'C'; $color = 'text-orange-600'; }
            else { $grade = 'D'; $color = 'text-red-600'; }

            $student->present_count = $present;
            $student->leave_count = $leaves;
            $student->absent_count = $absent;
            $student->attendance_percentage = round($percentage, 1);
            $student->grade = $grade;
            $student->grade_color = $color;

            return $student;
        });

        return view('admin.reports.results', [
            'students' => $reportData,
            'startDate' => $request->start_date,
            'endDate' => $request->end_date,
            'totalDays' => $workdaysCount 
        ]);
    }
}