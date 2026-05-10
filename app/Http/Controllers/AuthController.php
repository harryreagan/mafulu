<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function login(Request $request): View|RedirectResponse
    {
        if ($request->session()->get(config('mafullu.admin_session_key'))) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $adminEmail = (string) config('mafullu.admin_email');
        $adminPassword = (string) config('mafullu.admin_password');

        if (
            $adminEmail !== ''
            && $adminPassword !== ''
            && strcasecmp($credentials['email'], $adminEmail) === 0
            && hash_equals($adminPassword, $credentials['password'])
        ) {
            Auth::logout();
            $request->session()->put(config('mafullu.admin_session_key'), true);
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'These credentials do not match our records.'])
                ->onlyInput('email');
        }

        $request->session()->forget(config('mafullu.admin_session_key'));
        $request->session()->regenerate();

        return redirect()->intended(route('account.index'));
    }

    public function register(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create($validated);

        Auth::login($user);
        $request->session()->forget(config('mafullu.admin_session_key'));
        $request->session()->regenerate();

        return redirect()->intended(route('account.index'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->forget(config('mafullu.admin_session_key'));
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
