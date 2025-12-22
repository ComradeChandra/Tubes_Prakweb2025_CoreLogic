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
}