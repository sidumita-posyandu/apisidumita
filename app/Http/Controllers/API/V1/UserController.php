<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['role'])->get();

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $users
        ]);
    }

    public function show($id)
    {
        $user = User::with(['role'])->findOrFail($id);

        return response()->json([
            'status' => true,
            'code' => 200,
            'data' => $user
        ]);
    }
}