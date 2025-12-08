<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    // ID dijagain, kolom lain bebas
    protected $guarded = ['id'];

    // DEFINISI RELASI: Belongs To (Milik Siapa?)
    // Service ini milik SATU Kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

/*
========== CATATAN PRIBADI (JANGAN DIHAPUS BIAR GAK LUPA) ==========

ini MODEL Service. Ini "Kepala Gudang" buat Unit Dagangan.

1. LOGIKA RELASI:
   function category() pake 'belongsTo' (Milik).
   Artinya: Si Unit ini gak bisa berdiri sendiri, dia harus punya Bos (Kategori).
   
   Contoh: Unit "Eastern Wolves" -> Milik Kategori "Tactical Combat".

2. KEGUNAANNYA:
   Nanti di kodingan Controller atau View, urg bisa panggil:
   $service->category->name
   Buat nampilin nama kategori dari unit tersebut tanpa ribet query manual lagi.
*/