<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['id_user', 'name', 'email', 'NIM', 'tanggal_lahir', 'telepon', 'kesukaan', 'jurusan_id', 'alamat'];

    //
        public function student()
    {
        return $this->belongsTo(Student::class, 'id_student');
    }

}
