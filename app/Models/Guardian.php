<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Guardian extends User
{
    //parent Model
    use HasFactory;
    protected $fillable = [
        'user_id',
        'phone',
        'whatsapp',
        'family_members'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function address()
    {
        return $this->hasOne(Address::class,'guardian_id','user_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'guardian_id','user_id');
    }

    // Retrieve guardians with pending or accepted students, optionally limit results
    public static function retrieveGuardiansWithStudents($limit = null,$status)
    {
        $alias = $status === 'مقبول' ? 'accepted_students_count' : 'pending_students_count';

        $query = DB::table('guardians')
            ->join('students', 'guardians.user_id', '=', 'students.guardian_id')
            ->join('users', 'users.id', '=', 'guardians.user_id')
            ->where('students.status', $status)
            ->select(
                'users.id',
                'users.name',
                'users.created_at',
                'guardians.phone',
                'users.passport_number',
                'users.commission_number',
                DB::raw("COUNT(students.id) as $alias")
            )
            ->groupBy(
                'users.id',
                'users.name',
                'users.created_at',
                'guardians.phone',
                'users.passport_number',
                'users.commission_number'
            )
            ->orderBy('users.created_at', 'desc');

        // Apply limit if specified
        if ($limit) {
            $query->take($limit);
        }

        return $query->get();
    }


    public static function retrieveGuardiansWithPendingDataStudents($limit = null)
    {
        $status = 'قيد الانتظار'; // Pending status

        $query = DB::table('guardians')
            ->join('students', 'guardians.user_id', '=', 'students.guardian_id')
            ->join('users as guardian_users', 'guardian_users.id', '=', 'guardians.user_id') // Guardian user table
            ->join('users as student_users', 'student_users.id', '=', 'students.user_id') // Student user table
            ->where('students.status', $status)
            ->select(
                'guardian_users.id as guardian_id',
                'guardian_users.name as guardian_name', // Guardian's name
                'guardian_users.email as guardian_email',
                'guardian_users.passport_number as guardian_passport_number',
                'guardians.phone as guardian_phone',
                'student_users.id as student_id',
                'student_users.name as student_name', // Student's name
                'students.relationship as relationship',
                'students.status as student_status',
                'guardian_users.created_at' // Include created_at in the SELECT clause
            )
            ->groupBy(
                'guardian_users.id',
                'guardian_users.name',
                'guardian_users.email',
                'guardian_users.passport_number',
                'guardians.phone',
                'student_users.id',
                'student_users.name',
                'students.relationship',
                'students.status',
                'guardian_users.created_at'
            ) // Add created_at to GROUP BY
            ->orderBy('guardian_users.created_at', 'desc');

        // Apply limit if specified
        if ($limit) {
            $query->take($limit);
        }

        return $query->get();
    }
}
