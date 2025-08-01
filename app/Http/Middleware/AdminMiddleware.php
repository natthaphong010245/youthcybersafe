<?php
// app/Http/Middleware/AdminMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ตรวจสอบว่าผู้ใช้ login และเป็น admin หรือไม่
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }

        // หากไม่ใช่ admin ให้ redirect ไปหน้า home หรือ login
        if (Auth::check()) {
            return redirect()->route('home')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบ');
    }
}