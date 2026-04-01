<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\WhatsAppService;

class HRController extends Controller
{
    /**
     * Show the list of users waiting for HR approval.
     */
    public function index()
    {
        // Fetch users who are NOT active and NOT admins
        $pendingUsers = User::where('is_active', false)
                            ->where('role', '!=', 'admin')
                            ->latest()
                            ->get();

        // Pointing to your verified directory: resources/views/admin/hr/index.blade.php
        return view('admin.hr.index', compact('pendingUsers'));
    }

    /**
     * NEW: Show the list of all active students (The missing method).
     * This handles the "Remove Access" / Student Management page.
     */
    public function studentList()
    {
        $students = User::where('role', 'student')
                        ->where('is_active', true)
                        ->latest()
                        ->get();

        // Ensure this view exists at resources/views/admin/students.blade.php
        return view('admin.students', compact('students'));
    }

    /**
     * Approve a single user and notify them via WhatsApp.
     */
    public function approve($id)
    {
        $user = User::findOrFail($id);
        
        $user->update([
            'is_active' => true
        ]);

        $this->sendApprovalNotification($user);

        return redirect()->back()->with('success', "User {$user->name} approved and notified via WhatsApp.");
    }

    /**
     * Bulk Approve multiple users at once.
     */
    public function bulkApprove(Request $request)
    {
        $ids = $request->ids; 

        if (!$ids || count($ids) === 0) {
            return redirect()->back()->with('error', 'Please select at least one student to approve.');
        }

        $users = User::whereIn('id', $ids)->get();

        foreach ($users as $user) {
            $user->update(['is_active' => true]);
            $this->sendApprovalNotification($user);
        }

        return redirect()->back()->with('success', count($ids) . ' students have been approved and notified.');
    }

    /**
     * Helper Method: Handle the WhatsApp Message Logic
     */
    private function sendApprovalNotification($user)
    {
        if ($user->phone) {
            WhatsAppService::send(
                $user->phone, 
                "🎊 *Account Approved!*\n\nHello {$user->name}, your account has been verified by HR. You can now log in to the Attendance System and start marking your presence."
            );
        }
    }
}