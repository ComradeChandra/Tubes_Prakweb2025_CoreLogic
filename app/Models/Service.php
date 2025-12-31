<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    // Guarded id biar aman, sisanya fillable
    protected $guarded = ['id'];

    // Relasi ke Category (Satu service punya satu kategori)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke ServiceImage (Satu service bisa punya banyak gambar carousel)
    public function images()
    {
        return $this->hasMany(ServiceImage::class);
    }

    /**
     * CATATAN PENGEMBANG: unit_size & unit_description
     * - unit_size: integer yang menunjukkan berapa personel dalam 1 unit.
     * - unit_description: teks singkat yang menjelaskan arti "1 unit" (mis: "1 unit = 1 personel").
     *
     * Digunakan di front-end supaya pelanggan paham apakah mereka menyewa 1 orang, 1 kendaraan, atau 1 tim.
     */
    public function getUnitLabelAttribute()
    {
        if ($this->unit_description) return $this->unit_description;
        return ($this->unit_size ?? 1) . ' personel per unit';
    }
}