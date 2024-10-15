<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


require __DIR__.'/auth.php';

Route::group(['middleware'=>'jwt.verify'],function(){
    ///Let's agree that the admin ca access any API

    ///these are routes for admin only
    Route::group(['middleware'=>'admin'],function (){
        Route::get('/allTrips',[\App\Http\Controllers\Api\AdminController::class,'trips']);
    });



    ///these are routes for admin and student
    Route::group(['middleware'=>'student'],function (){
        Route::get('/nextTrip',[\App\Http\Controllers\Api\StudentController::class,'trip']);
        Route::get('/trip/{id}',[\App\Http\Controllers\Api\StudentController::class,'getTrip']);
        Route::get('/subject-videos',[\App\Http\Controllers\Api\StudentController::class,'getStudentSubjectsWithVideos']);
        Route::get('/videos/{subject_id}',[\App\Http\Controllers\Api\StudentController::class,'getVideos']);
        Route::patch('/students/videos/mark/{id}', [\App\Http\Controllers\Api\StudentController::class, 'mark']);
        Route::get('/video/{id}',[\App\Http\Controllers\Api\StudentController::class,'getVideo']);

    });



    ///these are routes for admin and date entry
    Route::group(['middleware'=>'dataEntry'],function (){
        Route::post('/trip/add',[\App\Http\Controllers\Api\DataEntryController::class,'addTrip']);
        Route::delete('/trip/delete/{id}',[\App\Http\Controllers\Api\DataEntryController::class,'deleteTrip']);
        Route::put('/jobs/{id}/open', [\App\Http\Controllers\Api\DataEntryController::class, 'markJobOpen']);
        Route::get('/jobs', [\App\Http\Controllers\Api\DataEntryController::class, 'getAllJobs']);
        Route::get('/job/{id}', [\App\Http\Controllers\Api\DataEntryController::class, 'getJob']);
        Route::get('/countJobs',[\App\Http\Controllers\Api\DataEntryController::class,'applications']);
        Route::get('/countNewStudents',[\App\Http\Controllers\Api\DataEntryController::class,'newStudents']);
        Route::get('/countAcceptedStudents',[\App\Http\Controllers\Api\DataEntryController::class,'acceptedStudents']);
        Route::get('/parents/pendingStudents',[\App\Http\Controllers\Api\DataEntryController::class,'getParents']);
        Route::get('/parent/{id}',[\App\Http\Controllers\Api\DataEntryController::class,'getParent']);
        Route::put('/student/{studentId}/status',[\App\Http\Controllers\Api\DataEntryController::class,'updateStudentStatus']);
        Route::get('/student/{studentId}/subjects',[\App\Http\Controllers\Api\DataEntryController::class,'getSubjectsForStudent']);
        Route::get('/students/{gradeId}/{termId}',[\App\Http\Controllers\Api\DataEntryController::class,'getStudentsByGradeAndTerm']);
        Route::post('/results/add', [\App\Http\Controllers\Api\DataEntryController::class, 'addResults']);
        Route::post('/student/add',[\App\Http\Controllers\Api\DataEntryController::class,'addStudent']);
    });
    ///these are routes for admin and media
    Route::group(['middleware'=>'media'],function (){
        //Get Subjects By Grade and term
        Route::post('/subjects', [\App\Http\Controllers\Api\MediaController::class, 'getSubjects']);
        Route::post('/video/add',[\App\Http\Controllers\Api\MediaController::class,'createVideo']);
        Route::delete('/deleteVideo/{id}',[\App\Http\Controllers\Api\MediaController::class,'deleteVideo']);
    });
    ///these are routes for admin, media, dataEntry
    Route::group(['middleware' => ['jwt.verify', 'media.dataEntry']], function () {
        Route::get('/grades', [\App\Http\Controllers\Api\MediaController::class, 'grades']);
    });
    ///these are routes for admin and parent
    Route::group(['middleware'=>'parent'],function (){
        Route::get('/nextTrips',[\App\Http\Controllers\Api\GuardianController::class,'trips']);
        Route::post('/parent/login-child/{child_id}', [\App\Http\Controllers\Api\GuardianController::class, 'loginToChild']);
        Route::get('/myStudents/{guardianId}',[\App\Http\Controllers\Api\GuardianController::class,'getStudentsWithDetails']);
        Route::get('/grades-terms/{studentId}',[\App\Http\Controllers\Api\GuardianController::class,'getGradesAndHisTermsForStudent']);
        Route::get('/results/{studentId}/{gradeId}/{termId}',[\App\Http\Controllers\Api\GuardianController::class,'getResultsByGradeAndTerm']);
    });
});

Route::get('/trips',[\App\Http\Controllers\Api\LandingController::class,'trips']);
Route::post('/job/add', [\App\Http\Controllers\Api\LandingController::class, 'createJob']);
Route::post('/register',[\App\Http\Controllers\Api\LandingController::class,'addGuardianWithStudents']);
