<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
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

// ===================================
// APPOINTMENT ROUTES (CRUD + EXTRAS)
// ===================================

// Basic CRUD operations
Route::get('/appointments', [AppointmentController::class, 'index']);           // Get all appointments
Route::post('/appointments', [AppointmentController::class, 'store']);          // Create new appointment
Route::get('/appointments/{id}', [AppointmentController::class, 'show']);      // Get single appointment
Route::put('/appointments/{id}', [AppointmentController::class, 'update']);    // Update appointment
Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy']); // Delete appointment

// Additional appointment routes
Route::get('/appointments/doctor/{doctor_id}', [AppointmentController::class, 'getByDoctor']);  // Get appointments by doctor
Route::get('/appointments/patient/{patient_id}', [AppointmentController::class, 'getByPatient']); // Get appointments by patient
Route::get('/appointments/date/{date}', [AppointmentController::class, 'getByDate']);           // Get appointments by date
Route::get('/appointments/status/{status}', [AppointmentController::class, 'getByStatus']);     // Get appointments by status
Route::patch('/appointments/{id}/status', [AppointmentController::class, 'updateStatus']);      // Update appointment status

// ===================================
// PATIENT ROUTES (CRUD + EXTRAS)
// ===================================

// Basic CRUD operations
Route::get('/patients', [PatientController::class, 'index']);           // Get all patients
Route::post('/patients', [PatientController::class, 'store']);          // Create new patient
Route::get('/patients/{id}', [PatientController::class, 'show']);      // Get single patient
Route::put('/patients/{id}', [PatientController::class, 'update']);    // Update patient
Route::delete('/patients/{id}', [PatientController::class, 'destroy']); // Delete patient

// Additional patient routes
Route::get('/patients/search/{name}', [PatientController::class, 'searchByName']);                  // Search patients by name
Route::get('/patients/sex/{sex}', [PatientController::class, 'getBySex']);                         // Get patients by sex
Route::get('/patients/age/{min}/{max}', [PatientController::class, 'getByAgeRange']);             // Get patients by age range
Route::get('/patients/{id}/appointments', [PatientController::class, 'getPatientWithAppointments']); // Get patient with appointments

// ===================================
// DOCTOR ROUTES (CRUD + EXTRAS)
// ===================================

// Basic CRUD operations
Route::get('/doctors', [DoctorController::class, 'index']);           // Get all doctors
Route::post('/doctors', [DoctorController::class, 'store']);          // Create new doctor
Route::get('/doctors/{id}', [DoctorController::class, 'show']);      // Get single doctor
Route::put('/doctors/{id}', [DoctorController::class, 'update']);    // Update doctor
Route::delete('/doctors/{id}', [DoctorController::class, 'destroy']); // Delete doctor

// Additional doctor routes
Route::get('/doctors/search/{name}', [DoctorController::class, 'searchByName']);                  // Search doctors by name
Route::get('/doctors/{id}/appointments', [DoctorController::class, 'getDoctorWithAppointments']); // Get doctor with appointments

// ===================================
// AUTHENTICATION ROUTES
// ===================================
// Public routes (no authentication required)
Route::post('/register', [RegisterController::class, 'register']); // User registration
Route::post('/login', [RegisterController::class, 'login']); // User login

// Protected routes (requires authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [RegisterController::class, 'logout']); // User logout
});
