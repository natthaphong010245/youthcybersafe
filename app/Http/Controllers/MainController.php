<?php
// app/Http/Controllers/MainController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    /**
     * Show test login page for approved users who are not researchers
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
        
        // หากเป็น researcher ที่ได้รับอนุมัติ ให้ไปที่ dashboard
        if ($user->canAccessDashboard()) {
            return redirect()->route('dashboard');
        }
        
        // สำหรับผู้ใช้ทั่วไป (ครูที่ได้รับอนุมัติ)
        return view('test_login', [
            'user' => $user
        ]);
    }
    
    /**
     * Show main page after successful login for general users
     */
    public function index()
    {
        return view('main', [
            'user' => Auth::user()
        ]);
    }
}