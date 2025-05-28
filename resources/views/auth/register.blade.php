<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Register</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <form method="POST" action="{{ route('register') }}"
        class="max-w-md w-full p-8 bg-white rounded-xl shadow-lg space-y-5">
        @csrf

        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Buat Akun</h2>

        <!-- Tampilkan semua error di atas form -->
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <label for="name" class="block mb-2 text-sm font-medium text-gray-700">Nama</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}"
                placeholder="Masukkan nama lengkap" required
                class="w-full px-4 py-2 border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Masukkan email"
                required
                class="w-full px-4 py-2 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" id="password" placeholder="Minimal 8 karakter" required
                class="w-full px-4 py-2 border @error('password') border-red-500 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                minlength="8">
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-700">Konfirmasi
                Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi password"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                minlength="8">
        </div>

        <button type="submit"
            class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-all duration-300">
            Daftar
        </button>

        <p class="text-center text-sm text-gray-600 mt-4">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">
                Masuk di sini
            </a>
        </p>

    </form>

    <script>
        const successMessage = "{{ session('success') }}";
        const errorMessage = "{{ session('error') }}";

        document.addEventListener('DOMContentLoaded', function() {
            if (successMessage) {
                Swal.fire({
                    title: "Berhasil!",
                    text: successMessage,
                    icon: "success"
                });
            }

            if (errorMessage) {
                Swal.fire({
                    title: "Gagal!",
                    text: errorMessage,
                    icon: "error"
                });
            }
        });
    </script>

</body>

</html>