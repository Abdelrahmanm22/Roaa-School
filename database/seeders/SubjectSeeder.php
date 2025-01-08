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


            'الصف الأول الابتدائي' =>   ['التربية الفنية', 'الرياضيات','اللغة الإنجليزية', 'اللغة العربية', 'التربية الاسلامية'], //1
            'الصف الثاني الابتدائي' => ['التربية الفنية', 'الرياضيات','اللغة الإنجليزية', 'اللغة العربية', 'التربية الاسلامية'], //2
            'الصف الثالث الابتدائي' => ['التربية الفنية','العلوم', 'الرياضيات','اللغة الإنجليزية', 'اللغة العربية', 'التربية الاسلامية'],//3
            'الصف الرابع الابتدائي' => ['التربية الفنية','التكنولوجيا','العلوم', 'الرياضيات','اللغة الإنجليزية', 'اللغة العربية', 'التربية الاسلامية'],//4
            'الصف الخامس الابتدائي' => ['التربية الفنية','التاريخ','الجغرافيا','التكنولوجيا','العلوم', 'الرياضيات','اللغة الإنجليزية','اللغة العربية', 'التربية الاسلامية'],//5
            'الصف السادس الابتدائي' => ['التربية الفنية','التاريخ','الجغرافيا','التكنولوجيا','العلوم', 'الرياضيات','اللغة الإنجليزية','اللغة العربية', 'التربية الاسلامية'],//6

            'الصف الأول الإعدادي' =>   ['التربية الفنية','التاريخ','الجغرافيا','التكنولوجيا','العلوم', 'الرياضيات','اللغة الإنجليزية','اللغة العربية', 'التربية الاسلامية'],
            'الصف الثاني الإعدادي' => ['التربية التقنية','التربية الفنية','التاريخ','الجغرافيا','التكنولوجيا','العلوم', 'الرياضيات','اللغة الإنجليزية','اللغة العربية', 'التربية الاسلامية'],
            'الصف الثالث الإعدادي' => ['التربية التقنية','التاريخ','الجغرافيا','التكنولوجيا','العلوم', 'الرياضيات','اللغة الإنجليزية','اللغة العربية', 'التربية الاسلامية'],

            'الصف الأول الثانوي' =>   ['الدراسات','الجغرافيا','التاريخ','الحاسوب','الهندسية','الاحياء','الفيزياء', 'الكيمياء','الرياضيات','اللغة الإنجليزية','اللغة العربية','التربية الاسلامية'],
            'الصف الثاني الثانوي' => ['الحاسوب','الهندسية','الاحياء','الفيزياء', 'الكيمياء','الرياضيات المتخصصة','اللغة الإنجليزية','اللغة العربية','التربية الاسلامية'],
            'الصف الثالث الثانوي' => ['الفنون','أدب إنجليزي','الدراسات','الجغرافيا', 'التاريخ','الرياضيات الاساسية','اللغة الإنجليزية','اللغة العربية','التربية الاسلامية'],
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
