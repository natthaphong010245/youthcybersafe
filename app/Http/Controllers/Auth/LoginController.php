<?php

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

        // ตรวจสอบว่าผู้ใช้มีสิทธิ์เข้าถึงหรือไม่
        if (Auth::user()->role_user == 1) {
            // กำหนดให้ไปที่ test_login
            return redirect()->route('test_login');
        } else {
            Auth::logout();
            return back()->withErrors([
                'username' => 'คุณไม่มีสิทธิ์เข้าถึงระบบ',
            ]);
        }
    }

    return back()->withErrors([
        'username' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง',
    ]);
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}