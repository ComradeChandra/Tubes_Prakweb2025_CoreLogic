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
        Schema::table('services', function (Blueprint $table) {
            // Jumlah personel per unit (default 1)
            $table->integer('unit_size')->default(1)->after('price');
            // Penjelasan singkat unit, mis: "1 unit = 1 personel"
            $table->string('unit_description')->nullable()->after('unit_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['unit_size', 'unit_description']);
        });
    }
};
