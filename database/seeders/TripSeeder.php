<?php

namespace Database\Seeders;

use App\Models\Trip;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trips = [
            [
                'title'=>'رحلة الأهرامات',
                'date' => '2024-10-2',
                'day'=>'الأربع',
            ],
            [
                'title'=>'رحلة حديقة الحيوانات',
                'date' => '2024-11-11',
                'day'=>'الجمعه',
            ],
            [
                'title'=>'رحلة الفسطاط',
                'date' => '2024-12-12',
                'day'=>'السبت',
            ],
        ];
        foreach ($trips as $trip) {
            Trip::create($trip);
        }
    }
}
