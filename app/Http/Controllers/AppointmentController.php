<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        return response()->json(Appointment::all());
    }

    public function store(Request $request)
    {
        $appointment = Appointment::create($request->validate([
            'doctors_id' => 'required|exists:doctors,doctors_id',
            'patient_id' => 'required|exists:patients,patient_id',
            'date' => 'required|date',
            'time' => 'required',
        ]));
        return response()->json($appointment, 201);
    }

    public function show($id)
    {
        return response()->json(Appointment::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update($request->validate([
            'doctors_id' => 'sometimes|required|exists:doctors,doctors_id',
            'patient_id' => 'sometimes|required|exists:patients,patient_id',
            'date' => 'sometimes|required|date',
            'time' => 'sometimes|required',
        ]));
        return response()->json($appointment);
    }

    public function destroy($id)
    {
        Appointment::destroy($id);
        return response()->json(['message' => 'Deleted'], 204);
    }
}
