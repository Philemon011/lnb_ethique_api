<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;



class RoleController extends Controller
{

    public function index()
    {
        //get posts
        $role = Role::latest()->get();

        //return collection of posts as a resource
        return response([
            'success' => true,
            'data' => $role,
            'message' => "Liste des signalements",
        ], 200);
    }

    public function show(Role $role)
    {
        //return response
        return response([
            'success' => true,
            'role' => $role,
            'message' => "Liste des signalements",
        ], 200);
    }

    public function updateRole(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id',
        'role_id' => 'required|exists:roles,id',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $user = User::find($request->user_id);
    if (!$user) {
        return response()->json(['error' => 'Utilisateur non trouvé.'], 404);
    }

    $role = DB::table('roles')->where('id', $request->role_id)->first();
    if (!$role) {
        return response()->json(['error' => 'Rôle non trouvé.'], 404);
    }

    $user->role_id = $role->id;
    $user->save();

    return response()->json([
        'success' => true,
        'message' => 'Rôle mis à jour avec succès.',
        'user_id' => $user->id,
        'new_role_id' => $user->role_id,
        'niveau' => $role->niveau,
        'nomRole' => $role->lib,
    ], 200);
}
}
