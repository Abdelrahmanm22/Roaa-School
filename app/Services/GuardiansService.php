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
        return DB::table('guardians')
            ->join('students', 'guardians.user_id', '=', 'students.guardian_id')
            ->join('users', 'users.id', '=', 'guardians.user_id')
            ->where('students.status', 'قيد الانتظار')
            ->select('users.id', 'users.name', 'users.created_at', 'guardians.phone')
            ->distinct()
            ->paginate(10);
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
