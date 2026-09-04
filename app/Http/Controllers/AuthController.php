<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\AuditLog;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();

            AuditLog::log(
                action: 'user_login',
                entityType: User::class,
                entityId: Auth::id()
            );

            if (Auth::user()->isAdmin() || Auth::user()->isVerifier()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registos.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:50',
            'password' => 'required|string|min:8|confirmed',
            'province' => 'nullable|string|max:255',
            'municipality' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => 'donor', // Default standard user
            'status' => 'active',
            'province' => $validated['province'] ?? null,
            'municipality' => $validated['municipality'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        AuditLog::log(
            action: 'user_registered',
            entityType: User::class,
            entityId: $user::class,
            newValues: ['name' => $user->name, 'email' => $user->email]
        );

        return redirect()->route('dashboard')->with('success', 'Conta criada com sucesso! Bem-vindo à FundMe Angola.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Sessão terminada.');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot_password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        return back()->with('success', 'Se o email existir no nosso sistema, receberá as instruções para redefinição da palavra-passe.');
    }
}
