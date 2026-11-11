<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('pages.login.login');
    }

    public function login_post(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        // Deteksi otomatis: kalau formatnya email, login pakai kolom email,
        // kalau bukan, login pakai kolom username.
        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $credentials = [
            $loginField => $request->login,
            'password'  => $request->password,
        ];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['login' => 'Username/Email atau password salah.'])
                ->onlyInput('login');
        }

        $request->session()->regenerate();

        // Tabel users belum punya kolom is_active/role, jadi semua user
        // yang berhasil login langsung masuk ke dashboard.
        return redirect()
            ->intended(route('admin.dashboard'))
            ->with('success', 'Selamat datang, ' . Auth::user()->username . '!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logout berhasil!');
    }
}
