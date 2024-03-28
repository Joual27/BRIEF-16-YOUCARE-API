<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('guest')->group(function (){
    Route::post('/login',[\App\Http\Controllers\AuthController::class,'login']);
    Route::post('/register/organizer',[\App\Http\Controllers\AuthController::class,'organizerRegistration']);
    Route::post('/register/volunteer',[\App\Http\Controllers\AuthController::class,'volunteerRegistration']);
    Route::post('/logout',[\App\Http\Controllers\AuthController::class,'logout']);
});


Route::middleware('auth:api')->group(function (){
    Route::post('/announcement/create',[\App\Http\Controllers\AnnouncementController::class,'createAnnouncement']);
    Route::put('/announcement/update/{announcement}',[\App\Http\Controllers\AnnouncementController::class,'updateAnnouncement']);
    Route::delete('/announcement/delete/{announcement}',[\App\Http\Controllers\AnnouncementController::class,'deleteAnnouncement']);
    Route::get('/announcements/all',[\App\Http\Controllers\AnnouncementController::class,'allAnnouncements']);
    Route::post('/announcements/filter',[\App\Http\Controllers\AnnouncementController::class,'announcementsFilter']);
    Route::post('/application/create',[\App\Http\Controllers\VolunteerController::class,'applyForAnnouncement']);
    Route::get('/application/accept/{application}',[\App\Http\Controllers\OrganizerController::class,'acceptApplication']);
    Route::get('/application/reject/{application}',[\App\Http\Controllers\OrganizerController::class,'rejectApplication']);
});
