<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class RegisterController extends Controller
{

    

    public function listingAdminAndSuperAdmin()
    {
        //get posts
        $users = User::latest()->get();
        $users = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.id','users.name', 'users.email', 'roles.lib')
            ->whereIn('roles.lib', ['Admin', 'Super Admin'])
            ->orderBy('users.created_at', 'desc')
            ->get();

        //return collection of signalements as a resource

        return response([
            'success' => true,
            'data' => $users,
            'message' => "Liste des signalements",
        ], 200);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        // Ajouter le rôle utilisateur par défaut
        $role = DB::table('roles')->select('id', 'niveau', 'lib')->where('niveau', '=', 3)->first();
        if (!$role) {
            return response()->json(['error' => 'Le rôle par défaut n\'existe pas.'], 400);
        }
        $input['role_id'] = $role->id;

        $user = User::create($input);
        $token =  $user->createToken('MyApp')->plainTextToken;
        $user_id =  $user->id;
        $name =  $user->name;
        $email =  $user->email;
        $role_id = $user->role_id;

        return response([
            'user_id' => $user_id,
            'token' => $token,
            'name' => $name,
            'email' => $email,
            'role_id' => $role_id,
            'niveau' => $role->niveau,
            'nomRole' => $role->lib,
            'message' => "User register successfully.",
        ], 201);
    }
    public function registerToDashboard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        // Ajouter le rôle utilisateur par défaut
        $role = DB::table('roles')->select('id', 'niveau', 'lib')->where('niveau', '=', 2)->first();
        if (!$role) {
            return response()->json(['error' => 'Le rôle par défaut n\'existe pas.'], 400);
        }
        $input['role_id'] = $role->id;

        $user = User::create($input);
        $token =  $user->createToken('MyApp')->plainTextToken;
        $user_id =  $user->id;
        $name =  $user->name;
        $email =  $user->email;
        $role_id = $user->role_id;

        return response([
            'user_id' => $user_id,
            'token' => $token,
            'name' => $name,
            'email' => $email,
            'role_id' => $role_id,
            'niveau' => $role->niveau,
            'nomRole' => $role->lib,
            'message' => "User register successfully.",
        ], 201);
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            // Récupérer les informations du rôle associé à l'utilisateur
            $role = DB::table('roles')->select('id', 'niveau', 'lib')->where('id', '=', $user->role_id)->first();

            if (!$role) {
                return response()->json(['error' => 'Le rôle associé à cet utilisateur n\'existe pas.'], 400);
            }



            $token =  $user->createToken('MyApp')->plainTextToken;

            return response([
                'token' => $token,
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role_id' => $role->id,
                'niveau' => $role->niveau,
                'nomRole' => $role->lib,
                'message' => "User login successfully.",
            ], 200);
        } else {
            return response([
                'message' => "User not login.",
            ], 404);
        }
    }

    public function getAuthenticatedUser()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user =  $user;
            return response([
                'user' => $user,
                'message' => "User login successfully.",
            ], 200);
        } else {
            return response([
                'message' => "User not login.",
            ]);
        }
    }

    public function logout()
    {

        auth()->user()->tokens()->delete();
        return response([
            'message' => 'User logged out successfully',
        ], 200);
        // if (Auth::check()) {
        //     Auth::logout();
        //     return response([
        //         'message' => "User logged out successfully.",
        //     ]);
        // } else {
        //     return response([
        //         'message' => "User not logged in.",
        //     ]);
        // }
    }
}
