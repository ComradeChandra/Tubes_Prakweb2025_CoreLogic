<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kolom buat nyimpen path foto profil user
            // Nullable: artinya boleh kosong (nanti kita isi pake DiceBear API kalo kosong)
            $table->string('avatar')->nullable()->after('email');

            // Kolom buat nyimpen status membership (Gamification)
            // Default: 'Civilian' (Level 1)
            // Pilihan: Civilian, VIP, Elite
            $table->string('tier')->default('Civilian')->after('avatar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom kalo rollback
            $table->dropColumn(['avatar', 'tier']);
        });
    }
};
