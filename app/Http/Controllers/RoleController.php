<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{

    public function index()
    {
        //get posts
        $role = Role::latest()->paginate(200);

        //return collection of posts as a resource
        return response([
            'success' => true,
            'role' => $role,
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
}
