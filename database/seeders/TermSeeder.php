<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Term;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the terms
        $terms = [
            'الفصل الدراسي الأول',
            'الفصل الدراسي الثاني',
        ];

        // Fetch all grades
        $grades = Grade::all();

        foreach ($terms as $termName) {
            // Create the term
            $term = Term::firstOrCreate(['name' => $termName]);

            // Attach each term to all grades
            foreach ($grades as $grade) {
                $grade->terms()->attach($term->id);
            }
        }
    }
}
