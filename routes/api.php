<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\AppointmentController;
USE App\Http\Controllers\API\DoctorController;
USE App\Http\Controllers\API\PatientController; 


Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [RegisterController::class, 'logout']);

//FOR THE VUE.JS APPOINTMENT BOOKING FRONTEND
Route::get('/appointments', [AppointmentController::class, 'index']);
Route::post('/appointments', [AppointmentController::class, 'store']);
Route::get('/doctors', [DoctorController::class, 'index']);
Route::get('/patients', [PatientController::class, 'index']);