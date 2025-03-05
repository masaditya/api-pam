<?php

namespace App\Http\Controllers;

use App\Models\EmployeeDetails;
use App\Models\Patrol;
use App\Models\PatrolTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatrolController extends Controller
{
    public function getPatrolType()
    {
        return response()->json([
            'message' => 'Success',
            'data' => PatrolTypes::all(['id', 'name']),
        ], 200);
    }
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'patrol_type_id' => 'required|exists:patrol_types,id',
            'image' => 'nullable|file|mimes:jpg,jpeg,png',
            'description' => 'required|string',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'added_by' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $departmentId = EmployeeDetails::where('user_id', $request['added_by'])
        ->select('department_id', 'company_id')
        ->firstOrFail();

        // Create the patrol record
        $patrol = new Patrol();
        $patrol->name = $request['name'];
        $patrol->patrol_type_id = $request['patrol_type_id'];
        $patrol->description = $request['description'];
        $patrol->longitude = $request['longitude'];
        $patrol->latitude = $request['latitude'];
        $patrol->added_by = $request['added_by'];
        $patrol->location = $departmentId->department_id ?? NULL;
        $patrol->company_id = $departmentId->company_id ?? NULL;

        // Handle the image upload if present
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/patrols', 'public');
            $patrol->image = $imagePath;
        }

        // Save the patrol record
        $patrol->save();

        return response()->json([
            'success' => true,
            'data' => $patrol,
            'message' => 'Patrol successfully created.'
        ], 201);
    }
}
