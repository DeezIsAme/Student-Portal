<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Sederhana</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow p-4 flex justify-between items-center">
        <div class="text-xl font-bold text-blue-600">Student Portal</div>
        <div>
            <a href="#" class="text-gray-700 hover:text-blue-600 mx-2">Dashboard</a>
            <a href="#" class="text-gray-700 hover:text-blue-600 mx-2">Profile</a>
            <a href="#" class="text-gray-700 hover:text-blue-600 mx-2">Logout</a>
        </div>
    </nav>
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl font-semibold mb-6">Selamat Datang di Dashboard</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="text-blue-500 text-3xl mb-2">📚</div>
                <div class="text-lg font-semibold">Total Mata Kuliah</div>
                <div class="text-2xl font-bold">8</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="text-green-500 text-3xl mb-2">✅</div>
                <div class="text-lg font-semibold">Tugas Selesai</div>
                <div class="text-2xl font-bold">15</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="text-yellow-500 text-3xl mb-2">🕒</div>
                <div class="text-lg font-semibold">Tugas Belum Selesai</div>
                <div class="text-2xl font-bold">3</div>
            </div>
        </div>
        <div class="mt-10 bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Pengumuman</h2>
            <ul class="list-disc pl-5 space-y-2">
                <li>UTS dimulai tanggal 10 Juni 2024.</li>
                <li>Pengumpulan tugas akhir paling lambat 20 Juni 2024.</li>
                <li>Libur semester mulai 1 Juli 2024.</li>
            </ul>
        </div>
    </div>
</body>
</html>