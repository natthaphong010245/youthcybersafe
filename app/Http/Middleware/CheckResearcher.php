<?php
// app/Http/Middleware/CheckResearcher.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckResearcher
{

    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->canAccessDashboard()) {
            return $next($request);
        }

        if (Auth::check()) {
            if (!Auth::user()->isApproved()) {
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'username' => 'คุณไม่มีสิทธิ์เข้าถึงระบบ'
                ]);
            } else {
                return redirect()->route('home')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
            }
        } else {
            return redirect()->route('login')->withErrors([
                'username' => 'กรุณาเข้าสู่ระบบ'
            ]);
        }
    }
}