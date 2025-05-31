<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{

    protected $fillable = ['id_user', 'name', 'email', 'NIM', 'tanggal_lahir', 'telepon', 'kesukaan', 'jurusan_id', 'alamat', 'alamat_link'];

    //
    public function user()
    {
        return $this->belongsTo(UserAccount::class, 'id_user');
    }

    public function jurusan()
    {
        return $this->belongsTo(DataJurusan::class, 'jurusan_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'id_student');
    }
}
