<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function create(): RedirectResponse
    {
        return redirect()->route('login');
    }

    public function store(Request $request): RedirectResponse
    {
        return redirect()->route('login');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->forget(config('mafullu.admin_session_key'));
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
