<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        return response()->json(Doctor::all());
    }

    public function store(Request $request)
    {
        $doctor = Doctor::create($request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:25',
        ]));
        return response()->json($doctor, 201);
    }

    public function show($id)
    {
        return response()->json(Doctor::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->update($request->validate([
            'name' => 'sometimes|required|string|max:255',
            'contact_number' => 'sometimes|required|string|max:25',
        ]));
        return response()->json($doctor);
    }

    public function destroy($id)
    {
        Doctor::destroy($id);
        return response()->json(['message' => 'Deleted'], 204);
    }
}
