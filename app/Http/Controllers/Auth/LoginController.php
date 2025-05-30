<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\UserAccountAuthService; // Import service class Anda
use Illuminate\Validation\ValidationException; // Import ValidationException

class LoginController extends Controller
{
    protected $userAccountAuthService;

    public function __construct(UserAccountAuthService $userAccountAuthService)
    {
        $this->userAccountAuthService = $userAccountAuthService;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        try {
            $user = $this->userAccountAuthService->authenticate(
                $request->input('email'),
                $request->input('password')
            );

            // Jika autentikasi berhasil (mendapatkan objek user), lakukan login secara manual
            // Pastikan Anda menggunakan guard yang benar jika Anda memiliki beberapa guard
            Auth::guard('user_account_guard')->login($user, $request->boolean('remember')); // <--- UN-KOMENTARI BARIS INI!

            $request->session()->regenerate();
            return redirect()->intended('/index'); // ubah ke route tujuanmu

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->onlyInput('email');
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Terjadi kesalahan. Silakan coba lagi.',
            ])->onlyInput('email');
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('user_account_guard')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}