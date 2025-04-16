<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\log;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index()
    {
        try {
            $employees = Employees::orderBy('id', 'desc')->get();
            return response()->json(['success' => true, 'data' => $employees]);
        } catch (\Throwable $th) {
            log::error('Failed to fetch data employees: ' . $th->getMessage());
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
            'phone' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->messages(),
            ], 422);
        }

        try {
            $employees = Employees::create([
                'user_id' => $request->user_id,
                'phone' => $request->phone,
                'address' => $request->address,
                'is_active' => $request->is_active,
                'gender' => $request->gender,
            ]);
            return response()->json(['success' => true, 'Employees added success' . 'data' => $employees], 201);
        } catch (\Throwable $th) {
            log::error('Failed insert : ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $employees = Employees::with('user')->findOrFail($id);
            return response()->json(['success' => true, 'Show Data Success' . 'data' => $employees]);
        } catch (\Throwable $th) {
            log::error('Failed insert : ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
            'phone' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors(),
            ], 422);
        }

        try {
            $data = [
                'user_id' => $request->user_id,
                'phone' => $request->phone,
                'address' => $request->address,
                'is_active' => $request->is_active,
                'gender' => $request->gender,
            ];
            $employees = Employees::findOrFail($id);
            $employees->update($data);
            return response()->json(['success' => true, 'Employees update success' . 'data' => $employees]);
        } catch (\Throwable $th) {
            log::error('Failed update : ' . $th->getMessage());
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }
}
