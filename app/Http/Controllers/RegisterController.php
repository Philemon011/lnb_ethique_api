<?php

namespace App\Http\Controllers;

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
        // Ajouter le rôle par défaut

        $user = User::create($input);
        $token =  $user->createToken('MyApp')->plainTextToken;
        $name =  $user->name;

        return response([
            'token' => $token,
            'name' => $name,
            'message' => "User register successfully.",
        ], 201);
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $role= $user->role;

            $token=  $user->createToken('MyApp')->plainTextToken;
            $name=  $user->name;
            $email=  $user->email;
            $user=  $user;

            return response([
                'token' => $token,
                'name' => $name,
                'email' => $email,
                'user' => $user,
                'role' => $role,
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
