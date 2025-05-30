<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataJurusan extends Model
{
    protected $fillable = ['id_user', 'name', 'email', 'NIM', 'tanggal_lahir', 'telepon', 'kesukaan', 'jurusan_id', 'alamat'];

    //
        public function students()
    {
        return $this->hasMany(Student::class, 'jurusan_id');
    }

}
