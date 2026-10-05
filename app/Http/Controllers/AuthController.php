<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route($this->dashboardRouteForAuthenticatedUser());
        }

        return view('auth.login');
    }

    public function captchaImage(Request $request)
    {
        $alphabet = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $code = '';

        for ($index = 0; $index < 3; $index++) {
            $code .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }

        $request->session()->put('login_captcha', $code);

        $letters = [];
        foreach (str_split($code) as $index => $character) {
            $x = 47 + ($index * 38) + random_int(-3, 3);
            $y = 34 + random_int(-4, 4);
            $rotation = random_int(-14, 14);
            $letters[] = '<text x="' . $x . '" y="' . $y . '" transform="rotate(' . $rotation . ' ' . $x . ' ' . $y . ')" text-anchor="middle" font-family="Georgia,serif" font-size="29" font-weight="700" fill="#302879">' . $character . '</text>';
        }

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 52" role="img" aria-label="Kode keamanan">'
            . '<rect x="1" y="1" width="158" height="50" rx="8" fill="#f8fafc" stroke="#d9dded"/>'
            . '<path d="M14 37 148 15M18 13l126 26M26 46l101-37" stroke="#c8c7dc" stroke-width="1" opacity=".7"/>'
            . implode('', $letters)
            . '</svg>';

        return response($svg, 200, [
            'Content-Type' => 'image/svg+xml',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'captcha' => ['required', 'string', 'size:3'],
        ]);

        $expectedCaptcha = (string) $request->session()->pull('login_captcha');
        if ($expectedCaptcha === '' || ! hash_equals($expectedCaptcha, strtoupper(trim($credentials['captcha'])))) {
            return back()
                ->withErrors(['captcha' => 'Kode keamanan tidak sesuai.'])
                ->withInput($request->only('email'));
        }

        unset($credentials['captcha']);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->route($this->dashboardRouteForAuthenticatedUser());
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route($this->dashboardRouteForAuthenticatedUser());
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'digits:16', 'unique:users,nik'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms_accepted' => ['accepted'],
        ]);

        User::create($validated);

        return redirect()->route('login')->with('status', 'Akun berhasil dibuat. Silakan login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function dashboardRouteForAuthenticatedUser(): string
    {
        return Auth::user()->role === 'camat' ? 'camat.dashboard' : 'dashboard';
    }
}