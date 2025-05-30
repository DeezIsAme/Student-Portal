<?php

namespace App\Services;

use App\Models\UserAccount; // Pastikan Anda sudah membuat model UserAccount seperti yang dijelaskan sebelumnya
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserAccountAuthService
{
    /**
     * Attempts to authenticate a user from the 'user_account' table.
     *
     * @param string $email
     * @param string $password
     * @return UserAccount|null Returns the authenticated user account or null if authentication fails.
     * @throws ValidationException
     */
    public function authenticate(string $email, string $password): ?UserAccount
    {
        // 1. Validasi manual (opsional, jika Anda ingin validasi yang lebih spesifik di sini)
        // Jika Anda tetap ingin menggunakan $request->validate() di controller,
        // bagian ini bisa dilewati atau disesuaikan.
        // Untuk contoh ini, kita asumsikan validasi email/password kosong sudah dilakukan di controller.

        // 2. Cari user berdasarkan email di tabel 'user_account'
        $userAccount = UserAccount::where('email', $email)->first();

        // 3. Cek apakah user ditemukan dan password cocok (sudah di-hash)
        if (! $userAccount || ! Hash::check($password, $userAccount->password)) {
            // Jika tidak cocok, lempar ValidationException
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        // Jika cocok, kembalikan objek UserAccount
        return $userAccount;
    }
}