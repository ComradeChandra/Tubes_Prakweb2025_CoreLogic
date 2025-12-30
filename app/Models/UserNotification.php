<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/*
========== CATATAN PENGEMBANG (UserNotification) ==========
Model ini menyimpan notifikasi sederhana untuk user (in-app notifications).
Kolom utama:
- user_id: foreign key ke users
- title: judul singkat notifikasi
- message: pesan lengkap
- is_read: apakah sudah dibaca oleh user (default false)

Ringkasan use-case:
- Dibuat oleh admin saat verifikasi KTP berubah.
- Ditampilkan di halaman profil user.
*/
class UserNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'is_read',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
