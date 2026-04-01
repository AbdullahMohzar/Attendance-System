<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use App\Services\WhatsAppService;
use Carbon\Carbon;

class LeaveController extends Controller
{
    /**
     * Student: Submit Leave Request (Multi-Day Support)
     */
    public function store(Request $request)
    {
        // 1. Validate the date range and reason
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|min:3',
        ]);

        $user = auth()->user();

        // 2. Create the record with start and end dates
        $leave = LeaveRequest::create([
            'user_id'    => $user->id,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
            'reason'     => $request->reason,
            'status'     => 'pending', 
        ]);

        if ($leave) {
            // Calculate total days for the notification
            $start = Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date);
            $days = $start->diffInDays($end) + 1;
            
            // Format duration string for WhatsApp
            $duration = ($days == 1) 
                ? $start->format('M d, Y') 
                : $start->format('M d') . ' to ' . $end->format('M d, Y');

            // TRIGGER: Notify student via WhatsApp
            if ($user->phone) {
                WhatsAppService::send(
                    $user->phone, 
                    "🌴 *Leave Request Submitted*\n\nHello {$user->name}, your leave request for *{$duration}* ({$days} Day" . ($days > 1 ? "s" : "") . ") has been received and is pending admin approval."
                );
            }

            return redirect()->back()->with('success', "Leave request for {$days} day(s) submitted successfully!");
        }

        return redirect()->back()->with('error', 'Something went wrong while saving.');
    }

    /**
     * Admin: Approve/Reject Logic
     */
    public function updateStatus(Request $request, $id)
    {
        // Load the leave request WITH the user
        $leave = LeaveRequest::with('user')->findOrFail($id);
        
        $leave->update([
            'status'        => $request->status,
            'admin_comment' => $request->admin_comment 
        ]);

        // TRIGGER: Notify student of the decision
        if ($leave->user && $leave->user->phone) {
            $statusUpper = strtoupper($request->status);
            $emoji = ($request->status == 'approved') ? '✅' : '❌';
            $comment = $request->admin_comment ? "\n*Admin Note:* " . $request->admin_comment : "";

            // Format the dates for the message
            $start = Carbon::parse($leave->start_date);
            $end = Carbon::parse($leave->end_date);
            $duration = ($leave->start_date == $leave->end_date) 
                ? $start->format('M d, Y') 
                : $start->format('M d') . ' to ' . $end->format('M d, Y');

            WhatsAppService::send(
                $leave->user->phone, 
                "{$emoji} *Leave Request Update*\n\nYour leave request for *{$duration}* has been *{$statusUpper}*." . $comment
            );
        }

        return redirect()->back()->with('success', 'Leave processed and student notified via WhatsApp.');
    }
}