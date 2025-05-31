<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataJurusan extends Model
{
    use HasFactory;

    protected $table = 'data_jurusans'; // pastikan sesuai nama tabel

    protected $fillable = [
        'nama_jurusan', // sesuaikan dengan kolom yang ada di tabel
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'jurusan_id');
    }
}
