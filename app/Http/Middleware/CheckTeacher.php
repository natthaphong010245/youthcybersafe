<?php
//app/Http/Middleware/CheckTeacher.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckTeacher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated and approved
        if (!Auth::check() || !Auth::user()->isApproved()) {
            if (Auth::check()) {
                Auth::logout();
            }
            return redirect()->route('login')->withErrors([
                'username' => 'คุณไม่มีสิทธิ์เข้าถึงระบบ'
            ]);
        }

        $user = Auth::user();

        // Check if user is a teacher
        if (!$user->isTeacher()) {
            // If not a teacher, redirect based on their role
            if ($user->isResearcher() && $user->canAccessDashboard()) {
                return redirect()->route('dashboard');
            }
            
            // Default redirect for other roles
            return redirect()->route('test_login')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        return $next($request);
    }
}