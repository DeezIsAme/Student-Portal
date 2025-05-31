<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\StudentController;

// Route untuk login (halaman utama)
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login'])->name('login.submit');

// Route untuk register
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Route untuk logout - PILIH SALAH SATU SAJA
// Opsi 1: Logout via LoginController (Recommended)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Opsi 2: Jika ingin menggunakan StudentController untuk logout
// Route::post('/logout', [StudentController::class, 'logout'])->name('logout');

// Route untuk student management
Route::middleware('auth:user_account_guard')->group(function () {
    Route::get('/index', [StudentController::class, 'create']);
});

Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
Route::post('/students', [StudentController::class, 'store'])->name('students.store');

// Route untuk profile management
Route::get('/profile/{name?}', [StudentController::class, 'showProfile'])->name('profile.show');
Route::get('/profile/{name}/edit', [StudentController::class, 'editProfile'])->name('profile.edit');
Route::put('/profile/update', [StudentController::class, 'updateProfile'])->name('profile.update');

// Route untuk testing
Route::get('/test-jurusan', [StudentController::class, 'testJurusan']);

// Route fallback untuk error handling
Route::fallback(function () {
    return redirect()->route('login');
});
