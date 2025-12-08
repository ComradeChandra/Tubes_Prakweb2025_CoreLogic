<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Guarded = ['id'] artinya:
    // "Jagain kolom ID jangan sampe diisi manual, sisanya (name, slug) BOLEH diisi massal"
    protected $guarded = ['id'];

    // DEFINISI RELASI: One to Many
    // Satu Kategori punya BANYAK Service
    public function services()
    {
        return $this->hasMany(Service::class);
    }
}

/*
========== CATATAN PRIBADI (JANGAN DIHAPUS BIAR GAK LUPA) ==========

ini MODEL Kategori. Bayangin ini "Kepala Gudang" buat Kategori.

1. APA ITU MODEL?
   Ini file PHP yang tugasnya ngobrol sama database. urg gak perlu ngetik SQL manual (SELECT * FROM...),
   cukup panggil Category::all().

2. $guarded = ['id']:
   Ini satpam. Dia mastiin urg gak bisa sembarangan ngubah ID kategori.
   Tapi kolom lain kayak 'name' sama 'slug' dibebasin (fillable otomatis).

3. function services():
   Ini jembatan relasi. urg bilang:
   "Eh Kategori, lu tuh punya BANYAK anak buah (Service)."
   Jadi nanti urg bisa panggil: $category->services buat ngeliat semua unit di kategori itu.
*/