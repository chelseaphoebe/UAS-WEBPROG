<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PegawaiSeeder extends Seeder
{
    public function run()
    {
        DB::table('pegawai')->insert([
            [
                'nama' => 'Fadhil jaidi',
                'no_hp' => '085893930323',
                'gaji' => 1000000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'Fadhil',
                'no_hp' => '085893930323',
                'gaji' => 1000000,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama' => 'ilman23',
                'no_hp' => '0987654321',
                'gaji' => 1000000,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
} 