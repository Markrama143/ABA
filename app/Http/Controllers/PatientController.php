<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        return response()->json(Patient::all());
    }

    public function store(Request $request)
    {
        $patient = Patient::create($request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'sex' => 'required|string|max:10',
            'age' => 'required|integer',
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:25',
        ]));
        return response()->json($patient, 201);
    }

    public function show($id)
    {
        return response()->json(Patient::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
        $patient->update($request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255',
            'sex' => 'sometimes|required|string|max:10',
            'age' => 'sometimes|required|integer',
            'address' => 'sometimes|required|string|max:255',
            'contact_number' => 'sometimes|required|string|max:25',
        ]));
        return response()->json($patient);
    }

    public function destroy($id)
    {
        Patient::destroy($id);
        return response()->json(['message' => 'Deleted'], 204);
    }
}
