<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\TypeSignalement;
use App\Http\Resources\PostResource;

class TypeSignalementController extends Controller
{
    public function index()
    {
        //get status
        $typeSignalement = TypeSignalement::latest()->get();

        //return collection of posts as a resource
        return response([
            'success' => true,
            'data' => $typeSignalement,
            'message' => "Liste des types de Signalement",
        ], 200);
    }


    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            "libelle" => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        TypeSignalement::create([
            "libelle" => $request->libelle,
        ]);

        //return response
        return response([
            'success' => true,
            'message' => "le type de Signalement a été bien enrégistré !",
        ], 201);
    }

    public function update(Request $request, TypeSignalement $typeSignalement)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'libelle' => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $typeSignalement->update([
            "libelle" => $request->libelle,
        ]);


        //return response
        return response([
            'success' => true,
            'message' => "le type de Signalement a été bien modifié !",
        ], 200);
    }

    public function destroy(TypeSignalement $typeSignalement)
    {

        //delete status
        $typeSignalement->delete();

        //return response
        return response([
            'success' => true,
            'message' => "le type de Signalement a été bien supprimé !",
        ], 200);
    }
}
