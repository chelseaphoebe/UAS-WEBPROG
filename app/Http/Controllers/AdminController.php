<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cek langsung ke tabel admin-login di database
        $admin = DB::table('admin_login')
            ->where('email', $credentials['email'])
            ->where('password', $credentials['password'])
            ->first();

        if ($admin) {
            // Simpan email admin di session
            session(['admin_email' => $admin->email]);
            
            // Redirect ke halaman home
            return redirect()->route('Home');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        // Hapus semua data session admin
        $request->session()->forget(['admin_email']);
        
        // Invalidate session dan regenerate token
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Redirect ke halaman home alih-alih halaman utama
        return redirect()->route('Home')->with('success', 'Berhasil logout');
    }
} 