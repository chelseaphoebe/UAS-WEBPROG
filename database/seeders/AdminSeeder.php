<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admin_login')->insert([
            [
                'email' => 'admin@admin.com',
                'password' => 'admin123',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
