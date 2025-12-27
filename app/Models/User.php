<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // Update: Perlu nambahin 'username' di sini karena logic register sekarang auto-generate username.
        // Kalau gak dimasukin, Laravel bakal nge-drop datanya sebelum masuk DB (Mass Assignment Protection).
        'username',
        // Update: 'role' juga wajib masuk fillable biar kita bisa set user sebagai 'admin' atau 'customer'.
        'role',
        // Update Sprint 3: Avatar & Tier
        'avatar',
        'tier',
        // Update: Data Lengkap User
        'nik',
        'id_card_path', // Path foto KTP
        'phone',
        'address',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * ACCESSOR: Get Avatar URL
     * Logic: Kalau user punya avatar di DB -> Pake itu.
     * Kalau gak punya -> Pake DiceBear API (Public API Integration).
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // Public API Integration: DiceBear
        // Kita pake style 'initials' biar muncul inisial nama user.
        $name = urlencode($this->name);
        return "https://api.dicebear.com/9.x/initials/svg?seed={$name}&backgroundColor=b91c1c&textColor=ffffff";
    }

    /**
     * RELASI: User punya banyak Order
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}