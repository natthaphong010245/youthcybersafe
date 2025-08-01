<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleUser
{
    public function handle(Request $request, Closure $next): Response
{
    if (Auth::check() && Auth::user()->role_user == 1) {
        return $next($request);
    }

    Auth::logout();
    return redirect()->route('login')->withErrors([
        'username' => 'คุณไม่มีสิทธิ์เข้าถึงระบบ',
    ]);
}
}