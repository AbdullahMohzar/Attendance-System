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

        return view('admin.hr.index', compact('pendingUsers'));
    }

    /**
     * Approve a user and notify them via WhatsApp.
     */
    public function approve($id)
    {
        $user = User::findOrFail($id);
        
        $user->update([
            'is_active' => true
        ]);

        // TRIGGER: Notify student via WhatsApp
        if ($user->phone) {
            WhatsAppService::send(
                $user->phone, 
                "🎊 *Account Approved!*\n\nHello {$user->name}, your account has been verified by HR. You can now log in to the Attendance System and start marking your presence."
            );
        }

        return redirect()->back()->with('success', "User {$user->name} approved and notified via WhatsApp.");
    }
}