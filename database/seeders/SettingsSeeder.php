<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            [
                'key' => 'academic_year',
                'value' => '2024',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'last_student_code',
                'value' => '5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
