<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{

    public function testLogin()
    {
        $user = Auth::user();
        
        if (!$user || !$user->isApproved()) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'username' => 'คุณไม่มีสิทธิ์เข้าถึงระบบ'
            ]);
        }
        
        if ($user->canAccessDashboard()) {
            return redirect()->route('dashboard');
        }
        
        if ($user->isTeacher() && $user->isApproved()) {
            return redirect()->route('teacher.dashboard');
        }
        
        return view('test_login', [
            'user' => $user
        ]);
    }
    
    /**
     * Get dashboard route based on user role and status
     */
    public static function getDashboardRoute($user)
    {
        if (!$user->isApproved()) {
            return route('home');
        }
        
        if ($user->isResearcher() && $user->canAccessDashboard()) {
            return route('dashboard');
        } elseif ($user->isTeacher()) {
            return route('teacher.dashboard');
        } else {
            return route('test_login');
        }
    }

    /**
     * Check if user can access any dashboard
     */
    public static function canAccessAnyDashboard($user)
    {
        return $user->isApproved() && ($user->canAccessDashboard() || $user->isTeacher());
    }
}