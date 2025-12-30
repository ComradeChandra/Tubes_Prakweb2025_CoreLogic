<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
Migration ini menambahkan kolom `ktp_verified` ke tabel users.
- Tipe: boolean (default false)
- Tujuan: menandai bahwa file KTP user telah diverifikasi oleh admin.
Rollback akan menghapus kolom ini.
*/

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('ktp_verified')->default(false)->after('id_card_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('ktp_verified');
        });
    }
};
