<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        /* Custom responsive breakpoints for better mobile experience */
        @media (max-width: 480px) {
            .mobile-padding {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen py-4 sm:py-8 px-2 sm:px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-2">Student Profile</h1>
                <p class="text-blue-100 text-sm sm:text-base">Profil Mahasiswa</p>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-3 sm:px-4 py-2 sm:py-3 rounded mb-4 sm:mb-6 shadow-md">
                <div class="flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm sm:text-base">{{ session('success') }}</span>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-3 sm:px-4 py-2 sm:py-3 rounded mb-4 sm:mb-6 shadow-md">
                <div class="flex items-center">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm sm:text-base">{{ session('error') }}</span>
                </div>
            </div>
            @endif

            <!-- Profile Card -->
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-xl sm:shadow-2xl overflow-hidden">
                <!-- Header Section -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                    <div class="flex flex-col sm:flex-row items-center sm:justify-between space-y-4 sm:space-y-0">
                        <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4 lg:space-x-6 text-center sm:text-left">
                            <!-- Profile Avatar -->
                            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-white rounded-full flex items-center justify-center shadow-lg flex-shrink-0">
                                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="text-white">
                                <h2 class="text-xl sm:text-2xl font-bold">{{ $student->name }}</h2>
                                <p class="text-blue-100 text-sm sm:text-base">{{ $student->NIM }}</p>
                                <p class="text-blue-100 text-sm sm:text-base">{{ $student->jurusan->nama_jurusan ?? 'Jurusan tidak ditemukan' }}</p>
                            </div>
                        </div>

                        <!-- Action Buttons - Update bagian tombol di profile_user.blade.php -->
                        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                            <a href="{{ route('profile.edit', ['name' => $student->name]) }}"
                                class="bg-white text-blue-600 px-4 sm:px-6 py-2 rounded-lg font-semibold hover:bg-blue-50 transition-colors duration-200 flex items-center justify-center text-sm sm:text-base">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a>
                            <a href="{{ route('logout') }}"
                                class="bg-transparent border-2 border-white text-white px-4 sm:px-6 py-2 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors duration-200 text-sm sm:text-base">
                                Logout
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Profile Details -->
                <div class="p-4 sm:p-6 lg:p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
                        <!-- Personal Information -->
                        <div class="space-y-4 sm:space-y-6">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 border-b-2 border-blue-200 pb-2">
                                Informasi Pribadi
                            </h3>

                            <div class="space-y-3 sm:space-y-4">
                                <div class="flex items-start space-x-3">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs sm:text-sm text-gray-500">Nama</p>
                                        <p class="font-semibold text-gray-800 text-sm sm:text-base break-words">{{ $student->name }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 011 1v1a1 1 0 01-1 1h-1v13a2 2 0 01-2 2H6a2 2 0 01-2-2V7H3a1 1 0 01-1-1V5a1 1 0 011-1h4z"></path>
                                    </svg>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs sm:text-sm text-gray-500">NIM</p>
                                        <p class="font-semibold text-gray-800 text-sm sm:text-base">{{ $student->NIM }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs sm:text-sm text-gray-500">Email</p>
                                        <p class="font-semibold text-gray-800 text-sm sm:text-base break-all">{{ $student->email }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 6v6m-4-6h8"></path>
                                    </svg>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs sm:text-sm text-gray-500">Tanggal Lahir</p>
                                        <p class="font-semibold text-gray-800 text-sm sm:text-base">{{ \Carbon\Carbon::parse($student->tanggal_lahir)->format('d F Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Academic & Contact Information -->
                        <div class="space-y-4 sm:space-y-6">
                            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 border-b-2 border-blue-200 pb-2">
                                Informasi Akademik & Kontak
                            </h3>

                            <div class="space-y-3 sm:space-y-4">
                                <div class="flex items-start space-x-3">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs sm:text-sm text-gray-500">Jurusan</p>
                                        <p class="font-semibold text-gray-800 text-sm sm:text-base">{{ $student->jurusan->nama_jurusan ?? 'Tidak ditemukan' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs sm:text-sm text-gray-500">Telepon</p>
                                        <p class="font-semibold text-gray-800 text-sm sm:text-base">{{ $student->telepon }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs sm:text-sm text-gray-500">Kesukaan</p>
                                        <p class="font-semibold text-gray-800 text-sm sm:text-base">{{ $student->kesukaan }}</p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs sm:text-sm text-gray-500">Alamat</p>
                                        <p class="font-semibold text-gray-800 text-sm sm:text-base break-words">{{ $student->alamat }}</p>
                                        @if($student->alamat_link)
                                        <a href="{{ $student->alamat_link }}" target="_blank"
                                            class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm inline-flex items-center mt-1 hover:underline">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                            Lihat di Maps
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>