<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient; // <-- 1. IMPORT PATIENT MODEL
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- 2. IMPORT DB FOR TRANSACTIONS

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ... (your existing index method is fine)
        $appointments = Appointment::with(['doctor', 'patient'])
                                    ->orderBy('date', 'asc')
                                    ->orderBy('time', 'asc')
                                    ->get();

        return response()->json($appointments);
    }


    /**
     * Store a newly created resource in storage.
     * * --- THIS IS THE UPDATED METHOD ---
     */
    public function store(Request $request)
    {
        // 1. Validate ALL incoming data (appointment + patient)
        $validated = $request->validate([
            // Appointment details
            'doctors_id' => 'required|integer|exists:doctors,doctors_id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i', // Assumes "14:30"
            
            // Patient details
            'patient_name' => 'required|string|max:255',
            'patient_email' => 'required|email|max:255',
            'patient_sex' => 'required|string|max:50',
            'patient_age' => 'required|integer|min:0|max:120',
            'patient_address' => 'required|string|max:255',
            'patient_contact_number' => 'required|string|max:50',
        ]);

        $patient = null;
        $appointment = null;

        // 2. Use a transaction. If patient creation fails, 
        // the appointment won't be created either.
        try {
            DB::transaction(function () use ($validated, &$patient, &$appointment) {
                
                // 3. Find patient by email or create them if they don't exist
                $patient = Patient::updateOrCreate(
                    ['email' => $validated['patient_email']], // Key to find patient
                    [ // Data to create or update with
                        'name' => $validated['patient_name'],
                        'sex' => $validated['patient_sex'],
                        'age' => $validated['patient_age'],
                        'address' => $validated['patient_address'],
                        'contact_number' => $validated['patient_contact_number'],
                    ]
                );

                // 4. Create the Appointment using the new/found patient's ID
                $appointment = Appointment::create([
                    'doctors_id' => $validated['doctors_id'],
                    'patient_id' => $patient->patient_id, // Use the new/found ID
                    'date' => $validated['date'],
                    'time' => $validated['time'],
                ]);
            });
        } catch (\Exception $e) {
            // Handle any error during the transaction
            return response()->json(['message' => 'An error occurred during booking.', 'error' => $e->getMessage()], 500);
        }

        // 5. Return the new appointment with relationships loaded
        $appointment->load(['doctor', 'patient']);
        return response()->json($appointment, 201);
    }
}