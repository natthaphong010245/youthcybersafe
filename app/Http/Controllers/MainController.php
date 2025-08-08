<?php
//app/Http/Controllers/MainController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    /**
     * Handle test login for approved users who are not researchers
     * This method is called after successful login to redirect users to appropriate dashboard
     */
    public function testLogin()
    {
        $user = Auth::user();
        
        // ตรวจสอบว่าผู้ใช้ได้รับอนุมัติแล้ว
        if (!$user || !$user->isApproved()) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'username' => 'คุณไม่มีสิทธิ์เข้าถึงระบบ'
            ]);
        }
        
        // ถ้าเป็น researcher ที่ได้รับอนุมัติ ให้ไปที่ researcher dashboard
        if ($user->canAccessDashboard()) {
            return redirect()->route('dashboard');
        }
        
        // ถ้าเป็น teacher ที่ได้รับอนุมัติ ให้ไปที่ teacher dashboard  
        if ($user->isTeacher() && $user->isApproved()) {
            return redirect()->route('teacher.dashboard');
        }
        
        // สำหรับผู้ใช้ทั่วไปอื่นๆ ที่ได้รับอนุมัติ
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