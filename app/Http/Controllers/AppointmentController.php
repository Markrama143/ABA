<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments.
     * GET /api/appointments
     */
    public function index()
    {
        try {
            $appointments = Appointment::with(['doctor', 'patient'])->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Appointments retrieved successfully',
                'data' => $appointments
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve appointments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created appointment.
     * POST /api/appointments
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'doctors_id' => 'required|exists:doctors,doctors_id',
                'patient_id' => 'required|exists:patients,patient_id',
                'date' => 'required|date|after_or_equal:today',
                'time' => 'required',
                'status' => 'nullable|in:pending,confirmed,completed,cancelled',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $appointment = Appointment::create($validator->validated());
            $appointment->load(['doctor', 'patient']);

            return response()->json([
                'success' => true,
                'message' => 'Appointment created successfully',
                'data' => $appointment
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified appointment.
     * GET /api/appointments/{id}
     */
    public function show($id)
    {
        try {
            $appointment = Appointment::with(['doctor', 'patient'])->find($id);

            if (!$appointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Appointment not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Appointment retrieved successfully',
                'data' => $appointment
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified appointment.
     * PUT/PATCH /api/appointments/{id}
     */
    public function update(Request $request, $id)
    {
        try {
            $appointment = Appointment::find($id);

            if (!$appointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Appointment not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'doctors_id' => 'sometimes|required|exists:doctors,doctors_id',
                'patient_id' => 'sometimes|required|exists:patients,patient_id',
                'date' => 'sometimes|required|date',
                'time' => 'sometimes|required',
                'status' => 'sometimes|in:pending,confirmed,completed,cancelled',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $appointment->update($validator->validated());
            $appointment->load(['doctor', 'patient']);

            return response()->json([
                'success' => true,
                'message' => 'Appointment updated successfully',
                'data' => $appointment
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified appointment.
     * DELETE /api/appointments/{id}
     */
    public function destroy($id)
    {
        try {
            $appointment = Appointment::find($id);

            if (!$appointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Appointment not found'
                ], 404);
            }

            $appointment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Appointment deleted successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get appointments by doctor.
     * GET /api/appointments/doctor/{doctor_id}
     */
    public function getByDoctor($doctor_id)
    {
        try {
            $appointments = Appointment::with(['doctor', 'patient'])
                ->where('doctors_id', $doctor_id)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Doctor appointments retrieved successfully',
                'data' => $appointments
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve doctor appointments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get appointments by patient.
     * GET /api/appointments/patient/{patient_id}
     */
    public function getByPatient($patient_id)
    {
        try {
            $appointments = Appointment::with(['doctor', 'patient'])
                ->where('patient_id', $patient_id)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Patient appointments retrieved successfully',
                'data' => $appointments
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve patient appointments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get appointments by date.
     * GET /api/appointments/date/{date}
     */
    public function getByDate($date)
    {
        try {
            $appointments = Appointment::with(['doctor', 'patient'])
                ->whereDate('date', $date)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Appointments for date retrieved successfully',
                'data' => $appointments
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve appointments by date',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get appointments by status.
     * GET /api/appointments/status/{status}
     */
    public function getByStatus($status)
    {
        try {
            $appointments = Appointment::with(['doctor', 'patient'])
                ->where('status', $status)
                ->get();

            return response()->json([
                'success' => true,
                'message' => "Appointments with status '{$status}' retrieved successfully",
                'data' => $appointments
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve appointments by status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update appointment status.
     * PATCH /api/appointments/{id}/status
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $appointment = Appointment::find($id);

            if (!$appointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Appointment not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'status' => 'required|in:pending,confirmed,completed,cancelled'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $appointment->update(['status' => $request->status]);
            $appointment->load(['doctor', 'patient']);

            return response()->json([
                'success' => true,
                'message' => 'Appointment status updated successfully',
                'data' => $appointment
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update appointment status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
