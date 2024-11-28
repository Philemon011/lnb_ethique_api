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
        $status = status::latest()->paginate(200);

        //return collection of posts as a resource
        return new PostResource(true, 'Liste des status', $status);
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
        return new PostResource(true, 'le Status a été bien enrégistré !', $status);
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
        return new PostResource(true, 'Status modifié avec succès', $status);
    }

    public function destroy(status $status)
    {

        //delete status
        $status->delete();

        //return response
        return new PostResource(true, 'Status supprimé', null);
    }


}
