<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Subject;
use App\Models\Term;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define subjects for different grades in Arabic
        $gradeSubjects = [
            'KG1' => ['الرياضيات', 'العلوم', 'الفن'],
            'KG2' => ['الرياضيات', 'العلوم', 'الفن'],
            'الصف الأول الابتدائي' => ['الإنجليزي', 'الرياضيات', 'اللغة العربية', 'التربية اإلاسلامية'],
            'الصف الثاني الابتدائي' => ['الإنجليزي', 'الرياضيات', 'اللغة العربية', 'التربية اإلاسلامية'],
            'الصف الثالث الابتدائي' => ['علوم طبيعية','الإنجليزي(٢)','الإنجليزي(١)', 'الرياضيات', 'اللغة العربية', 'التربية اإلاسلامية'],
            'الصف الرابع الابتدائي' => ['التربية الفنية','تكنولوجيا','علوم طبيعية','الإنجليزي(٢)','الإنجليزي(١)', 'الرياضيات', 'اللغة العربية', 'التربية اإلاسلامية'],
            'الصف الخامس الابتدائي' => ['جغرافيا','تاريخ','التربية الفنية','تكنولوجيا','علوم طبيعية','الإنجليزي(٢)','الإنجليزي(١)', 'الرياضيات', 'اللغة العربية', 'التربية اإلاسلامية'],
            'الصف السادس الابتدائي' => ['جغرافيا','تاريخ','Lucky Number','تكنولوجيا','العلوم','الإنجليزي(٢)','الإنجليزي(١)', 'الرياضيات', 'اللغة العربية', 'التربية اإلاسلامية'],
            'الصف الأول الإعدادي' => ['الرياضيات', 'العلوم', 'اللغة العربية', 'الإنجليزي', 'الدراسات الاجتماعية'],
            'الصف الثاني الإعدادي' => ['الرياضيات', 'العلوم', 'اللغة العربية', 'الإنجليزي', 'الدراسات الاجتماعية'],
            'الصف الثالث الإعدادي' => ['الرياضيات', 'العلوم', 'اللغة العربية', 'الإنجليزي', 'الدراسات الاجتماعية'],
            'الصف الأول الثانوي' => ['الرياضيات', 'الفيزياء', 'الكيمياء', 'اللغة العربية', 'الإنجليزي'],
            'الصف الثاني الثانوي' => ['الرياضيات', 'الفيزياء', 'الكيمياء', 'اللغة العربية', 'الإنجليزي'],
            'الصف الثالث الثانوي' => ['الرياضيات', 'الفيزياء', 'الكيمياء', 'اللغة العربية', 'الإنجليزي'],
        ];

        // Fetch all terms
        $terms = Term::all();

        foreach ($gradeSubjects as $gradeName => $subjects) {
            // Fetch the grade model
            $grade = Grade::where('name', $gradeName)->first();

            foreach ($subjects as $subjectName) {
                // Create or fetch the subject
                $subject = Subject::firstOrCreate(['name' => $subjectName]);

                // Attach the subject to each term that is linked to the grade with the grade_id
                foreach ($terms as $term) {
                    if ($grade->terms()->where('term_id', $term->id)->exists()) {
                        // Attach the subject with the grade_id
                        $subject->terms()->attach($term->id, ['grade_id' => $grade->id]);
                    }
                }
            }
        }
    }
}
