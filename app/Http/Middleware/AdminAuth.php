<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get(config('mafullu.admin_session_key'))) {
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}

