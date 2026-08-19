<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    /**
     * Show Admin login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $user = DB::table('users')->where('id', $userId)->first();
            if ($user && $user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
        }
        return view('admin.auth.login');
    }

    /**
     * Process Admin login request using DB facade.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Query user strictly using Query Builder
        $user = DB::table('users')
            ->where('email', $credentials['email'])
            ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Invalid administrator email address or password.']);
        }

        if ($user->role !== 'admin') {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Access Denied. This account does not have Administrator privileges.']);
        }

        Auth::loginUsingId($user->id, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')->with('success', 'Admin login successful. Welcome to Control Panel.');
    }

    /**
     * Log out Admin.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Admin session ended.');
    }
}
