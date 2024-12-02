<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Models\raison;
use Illuminate\Support\Facades\Validator;


class RaisonController extends Controller
{
    public function index()
    {
        //get status
        $raison = raison::latest()->paginate(200);

        //return collection of posts as a resource
        return new PostResource(true, 'Liste des raisons de Stockage', $raison);
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


        $raison=raison::create([
            "libelle"=> $request->libelle,
        ]);

        //return response
        return new PostResource(true, 'la raison a été bien enrégistré !', $raison);
    }

    // public function update(Request $request, raison $raison)
    // {
    //     //define validation rules
    //     $validator = Validator::make($request->all(), [
    //         'libelle'=> 'required',
    //     ]);

    //     //check if validation fails
    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 422);
    //     }

    //     $raison->update([
    //         "libelle"=> $request->libelle,
    //     ]);


    //     //return response
    //     return new PostResource(true, 'Raison modifié avec succès', $raison);
    // }

    // public function destroy(raison $raison)
    // {

    //     //delete status
    //     $raison->delete();

    //     //return response
    //     return new PostResource(true, 'Raison supprimé', null);
    // }
}
