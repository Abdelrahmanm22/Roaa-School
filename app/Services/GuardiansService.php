<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Guardian;
use Illuminate\Support\Facades\DB;
class GuardiansService
{
    protected $UserService;

    public function __construct(UsersService $UserService){
        $this->UserService = $UserService;
    }

    public function createGuardian($data)
    {
        $user = $this->UserService->createUser($data->input('guardian'),'parent');
        $guardian = Guardian::create([
            'user_id'=>$user->id,
            'phone'=>$data->input('guardian.phone'),
            'whatsapp'=>$data->input('guardian.whatsapp'),
            'family_members'=>$data->input('guardian.family_members'),
        ]);
        $this->createAddress($data,$guardian->user_id);
        return $guardian;
    }
    //guardians who have students with a status of "قيد الانتظار"
    public function getGuardiansWithPendingStudents()
    {
        // return DB::table('guardians')
        //     ->join('students', 'guardians.user_id', '=', 'students.guardian_id')
        //     ->join('users', 'users.id', '=', 'guardians.user_id')
        //     ->where('students.status', 'قيد الانتظار')
        //     ->select('users.id', 'users.name', 'users.created_at', 'guardians.phone')
        //     ->distinct()
        //     ->get();
//        return DB::table('guardians')
//            ->join('students', 'guardians.user_id', '=', 'students.guardian_id')
//            ->join('users', 'users.id', '=', 'guardians.user_id')
//            ->where('students.status', 'قيد الانتظار')
//            ->select('users.id', 'users.name', 'users.created_at', 'guardians.phone','users.passport_number','users.commission_number',DB::raw('COUNT(students.id) as pending_students_count'))
//            ->groupBy('users.id', 'users.name', 'users.created_at', 'guardians.phone', 'users.passport_number', 'users.commission_number')
//            ->orderBy('users.created_at', 'desc') // Order by created_at in descending order
//            ->get();
        // Retrieve all guardians with pending students without a limit
        return Guardian::retrieveGuardiansWithStudents(null,"قيد الانتظار");

    }

    // Method to get guardians with pending students and their data
    public function getGuardiansWithPendingDataStudents()
    {
        return Guardian::retrieveGuardiansWithPendingDataStudents();
    }

    //Recent guardians who have students with a status of "قيد الانتظار"
    public function getRecentGuardiansWithPendingStudents()
    {
        // Retrieve the most recent 10 guardians with pending students
        return Guardian::retrieveGuardiansWithStudents(10,"قيد الانتظار");
    }

    public function getGuardianByIdWithPendingStudentsAndDetails($guardianId)
    {
        return Guardian::with(['user', 'students'])
            ->where('user_id', $guardianId)
            ->whereHas('students', function ($query) {
                $query->where('status', 'قيد الانتظار');
            })
            ->first();
    }

    public function getGuardiansWithAcceptedStudents()
    {
        // Retrieve all guardians with Accepted students without a limit
        return Guardian::retrieveGuardiansWithStudents(null,"مقبول");
    }

    public function createAddress($data,$guardian_id)
    {
        Address::create([
            'guardian_id'=>$guardian_id,
            'city'=>$data->input('guardian.city'),
            'district'=>$data->input('guardian.district'),
            'building_number'=>$data->input('guardian.building_number'),
            'apartment_number'=>$data->input('guardian.apartment_number'),
        ]);
    }

}
