<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Form Input Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" />

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-down': 'slideDown 0.3s ease-out',
                        'modal-show': 'modalShow 0.3s ease-out',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes modalShow {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .map-container {
            height: 400px;
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <!-- Header -->
        <div class="text-center mb-8 animate-fade-in">
            <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent mb-2">
                Form Input Mahasiswa
            </h1>
            <p class="text-gray-600">Silakan lengkapi data mahasiswa dengan benar</p>
        </div>

        <!-- Success Alert -->
        @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-lg animate-slide-down">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <!-- Error Alert -->
        @if($errors->any() || session('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-lg animate-slide-down">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div class="text-red-700 font-medium">
                    @if(session('error'))
                    {{ session('error') }}
                    @endif
                    @if($errors->any())
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100 animate-fade-in">
            <form id="studentForm" method="POST" action="{{ route('students.store') }}" class="space-y-6">
                @csrf

                <!-- Nama -->
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-semibold text-gray-700">
                        Nama Lengkap
                        <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 hover:border-blue-400 @error('name') border-red-500 @enderror"
                        placeholder="Masukkan nama lengkap" />
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NIM -->
                <div class="space-y-2">
                    <label for="NIM" class="block text-sm font-semibold text-gray-700">
                        NIM (Nomor Induk Mahasiswa)
                        <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="NIM"
                        name="NIM"
                        value="{{ old('NIM') }}"
                        required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 hover:border-blue-400 @error('NIM') border-red-500 @enderror"
                        placeholder="Contoh: 12345678" />
                    @error('NIM')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-semibold text-gray-700">
                        Email
                        <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 hover:border-blue-400 @error('email') border-red-500 @enderror"
                        placeholder="contoh@email.com" />
                    @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Lahir -->
                <div class="space-y-2">
                    <label for="tanggal_lahir" class="block text-sm font-semibold text-gray-700">
                        Tanggal Lahir
                        <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="date"
                        id="tanggal_lahir"
                        name="tanggal_lahir"
                        value="{{ old('tanggal_lahir') }}"
                        required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 hover:border-blue-400 @error('tanggal_lahir') border-red-500 @enderror" />
                    @error('tanggal_lahir')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jurusan -->
                <div class="space-y-2">
                    <label for="jurusan_id" class="block text-sm font-semibold text-gray-700">
                        Jurusan
                        <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="jurusan_id"
                        name="jurusan_id"
                        required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 hover:border-blue-400 bg-white @error('jurusan_id') border-red-500 @enderror">
                        <option value="">-- Pilih Jurusan --</option>
                        @isset($jurusans)
                        @foreach($jurusans as $jurusan)
                        <option value="{{ $jurusan->id }}" {{ old('jurusan_id') == $jurusan->id ? 'selected' : '' }}>
                            {{ $jurusan->nama_jurusan }}
                        </option>
                        @endforeach
                        @else
                        <option value="1" {{ old('jurusan_id') == '1' ? 'selected' : '' }}>Teknik Informatika</option>
                        <option value="2" {{ old('jurusan_id') == '2' ? 'selected' : '' }}>Sistem Informasi</option>
                        <option value="3" {{ old('jurusan_id') == '3' ? 'selected' : '' }}>Teknik Komputer</option>
                        <option value="4" {{ old('jurusan_id') == '4' ? 'selected' : '' }}>Manajemen Informatika</option>
                        @endisset
                    </select>
                    @error('jurusan_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Telepon -->
                <div class="space-y-2">
                    <label for="telepon" class="block text-sm font-semibold text-gray-700">
                        Nomor Telepon
                        <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="telepon"
                        name="telepon"
                        value="{{ old('telepon') }}"
                        required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 hover:border-blue-400 @error('telepon') border-red-500 @enderror"
                        placeholder="Contoh: 08123456789" />
                    @error('telepon')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kesukaan -->
                <div class="space-y-2">
                    <label for="kesukaan" class="block text-sm font-semibold text-gray-700">
                        Hobi/Kesukaan
                        <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="kesukaan"
                        name="kesukaan"
                        value="{{ old('kesukaan') }}"
                        required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 hover:border-blue-400 @error('kesukaan') border-red-500 @enderror"
                        placeholder="Contoh: Membaca, Olahraga, Musik" />
                    @error('kesukaan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat dengan Maps -->
                <div class="space-y-2">
                    <label for="alamat" class="block text-sm font-semibold text-gray-700">
                        Alamat
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input
                            type="text"
                            id="alamat"
                            name="alamat"
                            value="{{ old('alamat') }}"
                            required
                            readonly
                            class="w-full px-4 py-3 pr-12 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 hover:border-blue-400 bg-gray-50 cursor-pointer @error('alamat') border-red-500 @enderror"
                            placeholder="Klik untuk memilih alamat di peta..."
                            onclick="openMapModal()" />
                        <button
                            type="button"
                            onclick="openMapModal()"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-blue-600 hover:text-blue-800 transition duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="text-sm text-gray-500">Klik pada input atau ikon untuk membuka peta dan memilih lokasi</p>
                    @error('alamat')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <!-- Hidden field untuk menyimpan link Google Maps -->
                    <input type="hidden" id="alamat_link" name="alamat_link" value="{{ old('alamat_link') }}" />

                    <!-- Preview alamat terpilih -->
                    <div id="alamat-preview" class="hidden mt-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-blue-800">Alamat Terpilih:</p>
                                <p id="alamat-text" class="text-sm text-blue-700 mt-1"></p>
                            </div>
                            <a id="alamat-link-preview" href="#" target="_blank" class="ml-3 inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 transition duration-200">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Lihat di Maps
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button
                        type="submit"
                        id="submitBtn"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-4 px-6 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-blue-300">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Data Mahasiswa
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-gray-500 text-sm">
                Pastikan semua data yang dimasukkan sudah benar sebelum menyimpan
            </p>
        </div>
    </div>

    <!-- Maps Modal -->
    <div id="mapModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden animate-modal-show">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Pilih Lokasi Alamat</h3>
                    <p class="text-sm text-gray-600 mt-1">Klik pada peta untuk memilih lokasi yang tepat</p>
                </div>
                <button
                    onclick="closeMapModal()"
                    class="text-gray-400 hover:text-gray-600 transition duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <!-- Search Box -->
                <div class="mb-4">
                    <div class="relative">
                        <input
                            type="text"
                            id="mapSearch"
                            placeholder="Cari alamat atau lokasi..."
                            class="w-full px-4 py-3 pr-12 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200" />
                        <button
                            onclick="searchLocation()"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-blue-600 hover:text-blue-800 transition duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Map Container -->
                <div id="map" class="map-container border border-gray-300"></div>

                <!-- Selected Location Info -->
                <div id="selectedLocationInfo" class="hidden mt-4 p-4 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="font-medium text-green-800">Lokasi Terpilih:</p>
                            <p id="selectedAddress" class="text-green-700 text-sm mt-1"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                <button
                    onclick="getCurrentLocation()"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Lokasi Saya
                </button>
                <div class="flex space-x-3">
                    <button
                        onclick="closeMapModal()"
                        class="px-6 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-200">
                        Batal
                    </button>
                    <button
                        id="confirmLocationBtn"
                        onclick="confirmLocation()"
                        disabled
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed transition duration-200">
                        Pilih Lokasi Ini
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

    <script>
        let map;
        let marker;
        let selectedLocation = null;

        // Initialize Leaflet Map
        function initMap() {
            // Default location (Jakarta)
            const defaultLocation = [-6.2088, 106.8456];

            map = L.map('map').setView(defaultLocation, 13);

            // Add OpenStreetMap tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Add click listener to map
            map.on('click', function(e) {
                placeMarker(e.latlng);
                getAddressFromLatLng(e.latlng);
            });
        }

        function placeMarker(location) {
            if (marker) {
                map.removeLayer(marker);
            }

            marker = L.marker([location.lat, location.lng], {
                draggable: true
            }).addTo(map);

            // Add drag listener to marker
            marker.on('dragend', function(e) {
                getAddressFromLatLng(e.target.getLatLng());
            });

            selectedLocation = location;
        }

        async function getAddressFromLatLng(latLng) {
            try {
                // Using Nominatim (OpenStreetMap) for reverse geocoding
                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latLng.lat}&lon=${latLng.lng}&addressdetails=1`);
                const data = await response.json();

                if (data && data.display_name) {
                    const address = data.display_name;
                    document.getElementById("selectedAddress").textContent = address;
                    document.getElementById("selectedLocationInfo").classList.remove("hidden");
                    document.getElementById("confirmLocationBtn").disabled = false;

                    selectedLocation = {
                        lat: latLng.lat,
                        lng: latLng.lng,
                        address: address,
                        mapsLink: `https://www.openstreetmap.org/?mlat=${latLng.lat}&mlon=${latLng.lng}&zoom=15`
                    };
                }
            } catch (error) {
                console.error('Error getting address:', error);
                // Fallback to coordinates if geocoding fails
                const address = `Lat: ${latLng.lat.toFixed(6)}, Lng: ${latLng.lng.toFixed(6)}`;
                document.getElementById("selectedAddress").textContent = address;
                document.getElementById("selectedLocationInfo").classList.remove("hidden");
                document.getElementById("confirmLocationBtn").disabled = false;

                selectedLocation = {
                    lat: latLng.lat,
                    lng: latLng.lng,
                    address: address,
                    mapsLink: `https://www.openstreetmap.org/?mlat=${latLng.lat}&mlon=${latLng.lng}&zoom=15`
                };
            }
        }

        async function searchLocation() {
            const query = document.getElementById("mapSearch").value;
            if (!query) return;

            try {
                // Using Nominatim for forward geocoding
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1&addressdetails=1`);
                const data = await response.json();

                if (data && data.length > 0) {
                    const result = data[0];
                    const location = {
                        lat: parseFloat(result.lat),
                        lng: parseFloat(result.lon)
                    };

                    map.setView([location.lat, location.lng], 15);
                    placeMarker(location);
                    getAddressFromLatLng(location);
                } else {
                    alert("Lokasi tidak ditemukan. Silakan coba kata kunci lain.");
                }
            } catch (error) {
                console.error('Error searching location:', error);
                alert("Terjadi kesalahan saat mencari lokasi. Silakan coba lagi.");
            }
        }

        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const location = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };
                        map.setView([location.lat, location.lng], 15);
                        placeMarker(location);
                        getAddressFromLatLng(location);
                    },
                    () => {
                        alert("Tidak dapat mengakses lokasi Anda. Pastikan GPS aktif dan izin lokasi diberikan.");
                    }
                );
            } else {
                alert("Browser Anda tidak mendukung Geolocation.");
            }
        }

        function openMapModal() {
            document.getElementById("mapModal").classList.remove("hidden");
            // Initialize map when modal opens if not already initialized
            setTimeout(() => {
                if (!map) {
                    initMap();
                } else {
                    // Invalidate size to fix display issues
                    map.invalidateSize();
                }
            }, 300);
        }

        function closeMapModal() {
            document.getElementById("mapModal").classList.add("hidden");
        }

        function confirmLocation() {
            if (selectedLocation) {
                // Set alamat value
                document.getElementById("alamat").value = selectedLocation.address;
                document.getElementById("alamat_link").value = selectedLocation.mapsLink;

                // Show preview
                document.getElementById("alamat-text").textContent = selectedLocation.address;
                document.getElementById("alamat-link-preview").href = selectedLocation.mapsLink;
                document.getElementById("alamat-preview").classList.remove("hidden");

                // Close modal
                closeMapModal();
            }
        }

        // Handle Enter key in search input
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('mapSearch').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchLocation();
                }
            });

            // Close modal when clicking outside
            document.getElementById('mapModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeMapModal();
                }
            });

            // Restore selected location from old input if exists
            const oldAlamat = document.getElementById("alamat").value;
            const oldAlamatLink = document.getElementById("alamat_link").value;

            if (oldAlamat && oldAlamatLink) {
                document.getElementById("alamat-text").textContent = oldAlamat;
                document.getElementById("alamat-link-preview").href = oldAlamatLink;
                document.getElementById("alamat-preview").classList.remove("hidden");
            }
        });

        // Form validation
        document.getElementById('studentForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const alamat = document.getElementById('alamat').value;

            if (!alamat) {
                e.preventDefault();
                alert('Silakan pilih alamat di peta terlebih dahulu.');
                return false;
            }

            // Disable submit button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menyimpan Data...
                </span>
            `;
        });

        // Input formatting for phone number
        document.getElementById('telepon').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-digits

            // Add country code if not present
            if (value.length > 0 && !value.startsWith('62') && !value.startsWith('08')) {
                if (value.startsWith('8')) {
                    value = '0' + value;
                }
            }

            e.target.value = value;
        });

        // Input formatting for NIM
        document.getElementById('NIM').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
            e.target.value = value;
        });

        // Auto-capitalize name input
        document.getElementById('name').addEventListener('input', function(e) {
            let value = e.target.value;
            // Capitalize first letter of each word
            value = value.toLowerCase().replace(/\b\w/g, l => l.toUpperCase());
            e.target.value = value;
        });

        // Validate birth date (should not be future date)
        document.getElementById('tanggal_lahir').addEventListener('change', function(e) {
            const selectedDate = new Date(e.target.value);
            const today = new Date();

            if (selectedDate > today) {
                alert('Tanggal lahir tidak boleh di masa depan.');
                e.target.value = '';
            }

            // Check if age is reasonable (between 15-60 years)
            const age = Math.floor((today - selectedDate) / (365.25 * 24 * 60 * 60 * 1000));
            if (age < 15 || age > 60) {
                const confirm = window.confirm(`Umur yang dimasukkan adalah ${age} tahun. Apakah ini benar?`);
                if (!confirm) {
                    e.target.value = '';
                }
            }
        });

        // Email validation
        document.getElementById('email').addEventListener('blur', function(e) {
            const email = e.target.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email && !emailRegex.test(email)) {
                e.target.setCustomValidity('Format email tidak valid');
            } else {
                e.target.setCustomValidity('');
            }
        });

        // Enhanced error handling for map loading
        window.addEventListener('error', function(e) {
            if (e.message && e.message.includes('Leaflet')) {
                console.error('Map loading error:', e);
                // Fallback for map errors
                const mapContainer = document.getElementById('map');
                if (mapContainer) {
                    mapContainer.innerHTML = `
                        <div class="flex items-center justify-center h-full bg-gray-100 rounded-lg">
                            <div class="text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"></path>
                                </svg>
                                <p>Peta tidak dapat dimuat</p>
                                <p class="text-sm">Silakan masukkan alamat secara manual</p>
                            </div>
                        </div>
                    `;
                }
            }
        });
    </script>
</body>

</html>