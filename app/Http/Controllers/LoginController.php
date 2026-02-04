<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Halaman Login Landing Page
    public function loginPage()
    {
        return view('login');
    }

    // Tampilkan form Sign In
    public function signinPage()
    {
        return view('signin');
    }

    // Proses Sign In
    public function signinProcess(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/menu')->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'loginError' => 'Email atau Password salah!',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }
}
