<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:student,teacher,hr'], // Added Role Validation
            'phone' => ['required', 'string', 'min:10'], // Added Phone Validation for WhatsApp
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,   // Assign selected role
            'phone' => $request->phone, // Save phone for notifications
            'is_active' => false,       // Default to false: Needs HR Approval
        ]);

        event(new Registered($user));

        // CRITICAL: We REMOVE Auth::login($user) because they aren't active yet.
        // If we log them in now, they bypass the HR approval check.

        return redirect()->route('login')->with('success', 'Registration successful! Your account is pending HR approval. You will receive a WhatsApp message once verified.');
    }
}