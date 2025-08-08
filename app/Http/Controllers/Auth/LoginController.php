<?php
//app/Http/Controllers/Auth/LoginController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('login&register.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'กรุณากรอกชื่อผู้ใช้',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // ตรวจสอบการอนุมัติ
            if (!$user->isApproved()) {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'คุณไม่มีสิทธิ์เข้าถึงระบบ',
                ]);
            }

            // Redirect based on user role
            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'username' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง',
        ]);
    }

    /**
     * Redirect user to appropriate dashboard based on their role
     */
    protected function redirectBasedOnRole($user)
    {
        switch($user->role) {
            case 'researcher':
                if ($user->canAccessDashboard()) {
                    return redirect()->route('dashboard');
                } else {
                    return redirect()->route('home')->with('message', 'กรุณารอการอนุมัติจากผู้ดูแลระบบ');
                }
                break;
                
            case 'teacher':
                if ($user->isApproved()) {
                    return redirect()->route('teacher.dashboard');
                } else {
                    Auth::logout();
                    return redirect()->route('login')->withErrors([
                        'username' => 'คุณไม่มีสิทธิ์เข้าถึงระบบ'
                    ]);
                }
                break;
                
            case 'admin':
                if ($user->isApproved()) {
                    return redirect()->route('dashboard'); // or admin specific route
                } else {
                    Auth::logout();
                    return redirect()->route('login')->withErrors([
                        'username' => 'คุณไม่มีสิทธิ์เข้าถึงระบบ'
                    ]);
                }
                break;
                
            default:
                // For other roles, redirect to test_login (existing behavior)
                return redirect()->route('test_login');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'ออกจากระบบเรียบร้อยแล้ว');
    }
}