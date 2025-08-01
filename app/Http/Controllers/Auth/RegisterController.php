<?php
// app/Http/Controllers/Auth/RegisterController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function create()
    {
        return view('login&register.register');
    }

    public function store(Request $request)
    {
        $messages = [
            'role.required' => 'กรุณาเลือก บทบาท',
            'role.in' => 'กรุณาเลือกบทบาทที่มี',
            'school.required' => 'กรุณาเลือก โรงเรียน',
            'name.required' => 'กรุณากรอกชื่อ',
            'name.max' => 'ชื่อต้องไม่เกิน 255 ตัวอักษร',
            'lastname.required' => 'กรุณากรอกนามสกุล',
            'lastname.max' => 'นามสกุลต้องไม่เกิน 255 ตัวอักษร',
            'username.required' => 'กรุณากรอก ชื่อผู้ใช้',
            'username.unique' => 'ชื่อผู้ใช้นี้ถูกใช้งานแล้ว',
            'username.max' => 'ชื่อผู้ใช้ต้องไม่เกิน 255 ตัวอักษร',
            'password.required' => 'กรุณากรอก รหัสผ่าน',
            'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร',
            'password_confirmation.required' => 'กรุณากรอก ยืนยันรหัสผ่าน',
            'password_confirmation.same' => 'การยืนยันรหัสผ่านไม่ตรงกัน',
        ];

        $rules = [
            'role' => 'required|in:teacher,researcher',
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => ['required', 'min:8'],
            'password_confirmation' => ['required', 'same:password'],
        ];

        // เพิ่มกฎสำหรับโรงเรียนหากเป็นครู
        if ($request->role === 'teacher') {
            $rules['school'] = 'required';
        }

        $request->validate($rules, $messages);

        try {
            // กำหนดค่า school (null สำหรับ researcher)
            $school = ($request->role === 'researcher') ? null : $request->school;

            // สร้างผู้ใช้ใหม่
            $user = User::create([
                'role' => $request->role,
                'role_user' => 0, // เริ่มต้นยังไม่อนุมัติ
                'school' => $school,
                'name' => $request->name,
                'lastname' => $request->lastname,
                'username' => $request->username,
                'password' => $request->password, // จะถูก hash อัตโนมัติใน model
            ]);
            
            return redirect()->route('login')->with('success', 'เจ้าหน้าที่กำลังตรวจสอบข้อมูลของคุณ กรุณารอการอนุมัติจากผู้ดูแลระบบ');
            
        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Registration error: ' . $e->getMessage());
            
            return back()->withInput()->withErrors([
                'general' => 'เกิดข้อผิดพลาดในการลงทะเบียน กรุณาลองใหม่อีกครั้ง'
            ]);
        }
    }
}