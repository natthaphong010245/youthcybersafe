<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRoleUser
{

    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isApproved()) {
            return $next($request);
        }

        if (Auth::check()) {
            Auth::logout();
        }
        
        return redirect()->route('login')->withErrors([
            'username' => 'คุณไม่มีสิทธิ์เข้าถึงระบบ'
        ]);
    }
}