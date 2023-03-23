<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Role;

class RoleController extends Controller
{
    public function index() 
    {
        $roles = Role::all();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $roles
        ]);

    }

    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'role' => 'required',
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }
        
        $role = Role::create($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data role berhasil ditambahkan",
            'data' => $role
        ], 200);
    }

    public function show($id) 
    {
        $roles = Role::findOrFail($id);

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $roles
        ]);

    }

    public function update(Request $request, Role $role)
    {
        $validasi = Validator::make($request->all(), [
            'role' => 'required',
        ]);
    
        if($validasi->fails()) {
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => "Data tidak dapat ditambahkan"
            ], 400);
        }
        
        $role->update($request->all());

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data role berhasil diubah",
            'data' => $role
        ], 200);
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => "Data role berhasil dihapus!",
        ], 200);
    }
}