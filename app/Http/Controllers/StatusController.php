<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\status;
use App\Http\Resources\PostResource;

class StatusController extends Controller
{
    //
    public function index()
    {
        //get status
        $status = status::latest()->get();

        //return collection of status as a resource
        return response([
            'success' => true,
            'data' => $status,
            'message' => "Liste des status",
        ], 200);
    }


    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
          "nom_status" =>'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $status=status::create([
            "nom_status"=> $request->nom_status,
        ]);

        //return response
        return response([
            'success' => true,
            'message' => "le status a été bien enrégistré !",
        ], 201);
    }

    public function update(Request $request, status $status)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'nom_status'=> 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $status->update([
            "nom_status"=> $request->nom_status,
        ]);


        //return response
        return response([
            'success' => true,
            'message' => "le status a été bien modifié !",
        ], 200);
    }

    public function destroy(status $status)
    {

        //delete status
        $status->delete();

        //return response
        return response([
            'success' => true,
            'message' => "le status a été bien supprimé !",
        ], 200);    }


}
