<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'penguji_1', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'penguji_2', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'penguji_3', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'kepala_sekolah', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
