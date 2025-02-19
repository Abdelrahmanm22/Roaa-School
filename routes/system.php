<?php
use Illuminate\Support\Facades\Route;


////apis for system
/////add guardian with students without get approvement from admin
Route::post('/addGuardianWithStudents', [\App\Http\Controllers\Api\SystemController::class, 'addGuardianWithStudents']);
////Retrieves guardians who have pending students
Route::get('/parentsWithPendingStudents',[\App\Http\Controllers\Api\SystemController::class,'getParentsWithPendingStudents']);
///update student status
Route::put('/student/{studentId}/status',[\App\Http\Controllers\Api\DataEntryController::class,'updateStudentStatus']);
///Route to get all grades in the system
Route::get('/allGrades',[\App\Http\Controllers\Api\MediaController::class,'grades']);
///Route to upgrade student
Route::post('/student/{studentId}/upgrade', [\App\Http\Controllers\Api\SystemController::class, 'upgradeStudentInSystem']);
//Route to get accepted students only
Route::get('/students/accepted',[\App\Http\Controllers\Api\DataEntryController::class,'getAcceptedStudents']);

