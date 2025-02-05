<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class RegisterController extends Controller
{
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
        $id_role_user = DB::table('roles')->select('id')->where('niveau', '=', 3)->first();
        $input['role_id'] = $id_role_user->id;

        $user = User::create($input);
        $token =  $user->createToken('MyApp')->plainTextToken;
        $name =  $user->name;
        $email =  $user->email;
        $role_id= $user->role_id;

        return response([
            'token' => $token,
            'name' => $name,
            'email' => $email,
            'role_id' => $role_id,
            'message' => "User register successfully.",
        ], 201);
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $role_id= $user->role_id;

            $token=  $user->createToken('MyApp')->plainTextToken;
            $name=  $user->name;
            $email=  $user->email;

            return response([
                'token' => $token,
                'name' => $name,
                'email' => $email,
                'role_id' => $role_id,
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
            $user=  $user;
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
