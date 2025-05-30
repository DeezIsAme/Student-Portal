<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\UserAccountAuthService; // Import service class Anda

class LoginController extends Controller
{
    // Inject service class melalui constructor (dependency injection)
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
        // Validasi input awal (tetap disarankan untuk input yang kosong)
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        try {
            // Panggil fungsi autentikasi dari service class
            $user = $this->userAccountAuthService->authenticate(
                $request->input('email'),
                $request->input('password')
            );

            // Jika autentikasi berhasil (mendapatkan objek user), lakukan login secara manual
            // Pastikan Anda menggunakan guard yang benar jika Anda memiliki beberapa guard
            $request->session()->regenerate(); // hindari session fixation
            return redirect()->intended('/index'); // ubah ke route tujuanmu

        } catch (ValidationException $e) {
            // Tangkap exception jika autentikasi gagal (email/password salah)
            return back()->withErrors($e->errors())->onlyInput('email');
        } catch (\Exception $e) {
            // Tangani error lain jika ada
            return back()->withErrors([
                'email' => 'Terjadi kesalahan. Silakan coba lagi.',
            ])->onlyInput('email');
        }
    }

    // Logout (opsional)
    public function logout(Request $request)
    {
        // Pastikan guard yang di-logout juga sesuai
        Auth::guard('user_account_guard')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}