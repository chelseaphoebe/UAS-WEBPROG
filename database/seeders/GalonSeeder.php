<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GalonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('harga_galon')->insert([
            [
                'nama_paket' => 'Paket Reguler',
                'price' => '12000',
                'description' => 'per galon',
                'benefit' => json_encode(["Layanan Pelanggan", 'Air Minum Premium', "Pengiriman Standar", "Galon Higienis"]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_paket' => 'Paket Agen',
                'price' => '10000',
                'description' => 'per galon (min. 10 galon)',
                'benefit' => json_encode(["Layanan Prioritas", 'Air Minum Premium', "Pengiriman Khusus Agen", "Galon Higienis" ]),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
