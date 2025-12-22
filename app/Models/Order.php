<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Guarded ID biar aman, sisanya boleh diisi massal
    protected $guarded = ['id'];

    // RELASI: Order milik satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // RELASI: Order berisi satu Service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
