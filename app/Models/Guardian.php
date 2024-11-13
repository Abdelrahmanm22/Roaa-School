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
}
