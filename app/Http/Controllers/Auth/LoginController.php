<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\UserAccountAuthService;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    protected $userAccountAuthService;

    public function __construct(UserAccountAuthService $userAccountAuthService)
    {
        $this->userAccountAuthService = $userAccountAuthService;
    }

    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Memproses login
    public function login(Request $request)
    {
        // Validasi input awal
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        try {
            // Panggil service untuk autentikasi
            $user = $this->userAccountAuthService->authenticate(
                $request->input('email'),
                $request->input('password')
            );

            // Login manual menggunakan guard yang sesuai
            Auth::guard('user_account_guard')->login($user);

            // Regenerasi session untuk keamanan
            $request->session()->regenerate();

            // Redirect ke halaman yang diinginkan
            return redirect()->intended('/index');

        } catch (ValidationException $e) {
            // Jika autentikasi gagal
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Jika ada kesalahan lain
            return back()->withErrors([
                'email' => 'Terjadi kesalahan. Silakan coba lagi.',
            ])->withInput();
        }
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::guard('user_account_guard')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
