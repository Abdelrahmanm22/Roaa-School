<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Guardian;

class GuardianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creating guardians
        $guardians = [
            [
                'user_id'=>User::where('name', 'محمد رمضان')->first()->id,
                'phone' => '01234567890',
                'whatsapp' => '01234567890',
                'family_members' => 4,
            ],
            [
                'user_id'=>User::where('name', 'أشرف عزب')->first()->id,
                'phone' => '09876543210',
                'whatsapp' => '09876543210',
                'family_members' => 4,
            ],
        ];

        foreach ($guardians as $guardian) {
            Guardian::create($guardian);
        }
    }
}
