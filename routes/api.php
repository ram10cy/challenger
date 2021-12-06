<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AppointmentController;

Route::post('login', [AuthController::class, 'authenticate']);
Route::post('register', [AuthController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('info', [AuthController::class, 'info']);

    //contacts
    Route::prefix('contact')->group(function (){
        Route::post('create', [ContactController::class, 'create']);
        Route::get('info', [ContactController::class, 'info']);
        Route::get('list', [ContactController::class, 'listContacts']);
        Route::post('update', [ContactController::class, 'update']);
        Route::get('delete', [ContactController::class, 'delete']);
    });

    //appointments
    Route::prefix('appointment')->group(function (){
        Route::post('create', [AppointmentController::class, 'create']);
        Route::get('info', [AppointmentController::class, 'info']);
        Route::get('list', [AppointmentController::class, 'listAppointments']);
        Route::post('update', [AppointmentController::class, 'update']);
        Route::get('delete', [AppointmentController::class, 'delete']);
    });



});
