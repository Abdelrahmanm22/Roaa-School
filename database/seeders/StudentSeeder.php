<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creating students and associating them with guardians
        $students = [
            [
                'user_id'=>User::where('name', 'عبدالرحمن محمد رمضان')->first()->id,
                'guardian_id' => User::where('name', 'محمد رمضان')->first()->id,
                'current_grade_id' => 6, // الصف السادس الابتدائي
                'current_term_id' => 1, // Assuming it's الفصل الدراسي الأول
                'code' => 'STU241',
                'SSN' => '12345678901234',
                'birthdate' => '2011-01-01',
                'gender' => 'ذكر',
                'status' => 'مقبول',
                'rank'=>'1',
                'relationship'=>'أب'
            ],
            [
                'user_id'=>User::where('name', 'خالد محمد رمضان')->first()->id,
                'guardian_id' => User::where('name', 'محمد رمضان')->first()->id,
                'current_grade_id' => 6, // الصف السادس الابتدائي
                'current_term_id' => 1, // Assuming it's الفصل الدراسي الأول
                'code' => 'STU245',
                'SSN' => '1234567890123665',
                'birthdate' => '2011-01-01',
                'gender' => 'ذكر',
                'status' => 'مقبول',
                'rank'=>'1',
                'relationship'=>'أب'
            ],
            [
                'user_id'=>User::where('name', 'كريم محمد رمضان')->first()->id,
                'guardian_id' => User::where('name', 'محمد رمضان')->first()->id,
                'current_grade_id' => 2, // الصف الثاني الابتدائي
                'current_term_id' => 1,
                'code' => 'STU242',
                'SSN' => '12345678901235',
                'birthdate' => '2016-01-01',
                'gender' => 'ذكر',
                'status' => 'مقبول',
                'rank'=>'4',
                'relationship'=>'أب'
            ],
            [
                'user_id'=>User::where('name', 'زياد أشرف عزب')->first()->id,
                'guardian_id'=>User::where('name', 'أشرف عزب')->first()->id,
                'current_grade_id' => 5, // الصف الخامس الابتدائي
                'current_term_id' => 1,
                'code' => 'STU243',
                'SSN' => '12345678901236',
                'birthdate' => '2013-01-01',
                'gender' => 'ذكر',
                'status' => 'مقبول',
                'rank'=>'1',
                'relationship'=>'أب'
            ],
            [
                'user_id'=>User::where('name', 'اياد أشرف عزب')->first()->id,
                'guardian_id'=>User::where('name', 'أشرف عزب')->first()->id,
                'current_grade_id' => 3, // الصف الثالث الابتدائي
                'current_term_id' => 1,
                'code' => 'STU244',
                'SSN' => '12345678901237',
                'birthdate' => '2015-01-01',
                'gender' => 'ذكر',
                'status' => 'مقبول',
                'rank'=>'3',
                'relationship'=>'أب'
            ],
        ];

        foreach ($students as $studentData) {
            // Create the Student instance and capture it
            $student = Student::create($studentData);

            // Attach the current grade and term for the student using the instance
            $student->grades()->attach($student->current_grade_id);
            $student->terms()->attach($student->current_term_id);
        }
    }
}
