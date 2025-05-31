<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['id_user', 'activity'];

    //
    public function student()
    {
        return $this->belongsTo(Student::class, 'id_student');
    }
}
