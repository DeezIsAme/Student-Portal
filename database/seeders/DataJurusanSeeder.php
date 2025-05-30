<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataJurusan;

class DataJurusanSeeder extends Seeder
{
    public function run(): void
    {
        $jurusans = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Matematika',
            'Teknik Pertambangan',
            'Biologi',
            'Fisika',
            'Kimia',
        ];

        foreach ($jurusans as $nama_jurusan) {
            DataJurusan::create([
                'nama_jurusan' => $nama_jurusan
            ]);
        }
    }
}
