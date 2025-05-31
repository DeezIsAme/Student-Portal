<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'activity',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke UserAccount
     * Sesuai dengan foreign key 'id_user' yang menunjuk ke tabel 'user_accounts'
     */
    public function userAccount()
    {
        return $this->belongsTo(UserAccount::class, 'id_user');
    }

    // Jika Anda masih ingin menggunakan relasi 'student' untuk backward compatibility
    public function student()
    {
        return $this->belongsTo(UserAccount::class, 'id_user');
    }
}
