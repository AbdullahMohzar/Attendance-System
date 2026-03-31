<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use App\Services\WhatsAppService;

class LeaveController extends Controller
{
    // Student: Submit Leave
    public function store(Request $request)
    {
        // 1. Validate
        $request->validate([
            'leave_date' => 'required|date',
            'reason' => 'required|string|min:3',
        ]);

        $user = auth()->user();

        // 2. Create the record
        $leave = \App\Models\LeaveRequest::create([
            'user_id' => $user->id,
            'leave_date' => $request->leave_date,
            'reason' => $request->reason,
            'status' => 'pending', 
        ]);

        if ($leave) {
            // TRIGGER: Notify student via WhatsApp (MUST be before the return)
            if ($user->phone) {
                \App\Services\WhatsAppService::send(
                    $user->phone, 
                    "📅 *Leave Request Submitted*\n\nHello {$user->name}, your leave request for *{$request->leave_date}* has been received and is pending admin approval."
                );
            }

            return redirect()->back()->with('success', 'Leave request submitted successfully!');
        }

        return redirect()->back()->with('error', 'Something went wrong while saving.');
    }

    // Admin: Approve/Reject Logic
    public function updateStatus(Request $request, $id)
    {
        // Load the leave request WITH the user so we can get their phone number
        $leave = \App\Models\LeaveRequest::with('user')->findOrFail($id);
        
        $leave->update([
            'status' => $request->status,
            'admin_comment' => $request->admin_comment 
        ]);

        // TRIGGER: Notify student of the decision
        if ($leave->user && $leave->user->phone) {
            $statusUpper = strtoupper($request->status);
            $emoji = ($request->status == 'approved') ? '✅' : '❌';
            $comment = $request->admin_comment ? "\n*Admin Note:* " . $request->admin_comment : "";

            \App\Services\WhatsAppService::send(
                $leave->user->phone, 
                "{$emoji} *Leave Request Update*\n\nYour leave request for *{$leave->leave_date}* has been *{$statusUpper}*." . $comment
            );
        }

        return redirect()->back()->with('success', 'Leave processed and student notified via WhatsApp.');
    }
}