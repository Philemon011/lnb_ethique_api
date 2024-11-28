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
        $typeSignalement = TypeSignalement::latest()->paginate(200);

        //return collection of posts as a resource
        return new PostResource(true, 'Liste des types de Signalement', $typeSignalement);
    }


    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
          "libelle" =>'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $typeSignalement=TypeSignalement::create([
            "libelle"=> $request->libelle,
        ]);

        //return response
        return new PostResource(true, 'le type de Signalement a été bien enrégistré !', $typeSignalement);
    }

    public function update(Request $request, TypeSignalement $typeSignalement)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'libelle'=> 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $typeSignalement->update([
            "libelle"=> $request->libelle,
        ]);


        //return response
        return new PostResource(true, 'type de Signalement modifié avec succès', $typeSignalement);
    }

    public function destroy(typeSignalement $typeSignalement)
    {

        //delete status
        $typeSignalement->delete();

        //return response
        return new PostResource(true, 'type de Signalement supprimé', null);
    }

}
