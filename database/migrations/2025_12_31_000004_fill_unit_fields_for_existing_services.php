<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('services')->where('slug', 'eastern-wolves-platinum')->update([
            'unit_size' => 3,
            'unit_description' => '1 unit = 3 highly trained operators (team leader + 2 operatives)'
        ]);

        DB::table('services')->where('slug', 'blackgold-team-gold')->update([
            'unit_size' => 2,
            'unit_description' => '1 unit = 2 operators (team leader + support)'
        ]);

        DB::table('services')->where('slug', 'k9-handler-trainer')->update([
            'unit_size' => 1,
            'unit_description' => '1 unit = 1 handler + 1 K-9'
        ]);

        DB::table('services')->where('slug', 'armored-vip-escort')->update([
            'unit_size' => 2,
            'unit_description' => '1 unit = 2 personnel (driver + close protection officer)'
        ]);

        DB::table('services')->where('slug', 'corelogic-statsec')->update([
            'unit_size' => 1,
            'unit_description' => '1 unit = 1 posted guard per shift'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('services')->whereIn('slug', [
            'eastern-wolves-platinum', 'blackgold-team-gold', 'k9-handler-trainer',
            'armored-vip-escort', 'corelogic-statsec'
        ])->update([
            'unit_size' => 1,
            'unit_description' => null
        ]);
    }
};
