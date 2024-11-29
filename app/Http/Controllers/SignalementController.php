<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Signalement;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\DB;


class SignalementController extends Controller
{

    public function index()
    {
        //get posts
        $signalement = Signalement::latest()->paginate(200);
        $signalement = DB::table('signalements')
                        ->join('type_signalements','signalements.type_de_signalement_id' , '=', 'type_signalements.id')
                        ->join('statuses','signalements.status_id' , '=', 'statuses.id')
                        ->select('signalements.*' , 'type_signalements.libelle'  ,'statuses.nom_status' )
                        ->get();

        //return collection of posts as a resource
        return new PostResource(true, 'Liste des Signalements', $signalement);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_de_signalement_id' => 'required|exists:type_signalements,id',
            'description' => 'required|string',
            'piece_jointe' => 'nullable|file|mimes:jpeg,png,pdf,doc,docx,mp4,mkv,avi,mov|max:10240',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Récupération dynamique de l'ID du statut "Non traité"
        $statusNonTraite = \App\Models\Status::where('nom_status', 'Non traité')->first();
        if (!$statusNonTraite) {
            return response()->json([
                'message' => 'Le statut "Non traité" est introuvable. Veuillez vérifier vos données.',
            ], 500);
        }

        // Génération du code de suivi
        $codeDeSuivi = strtoupper(uniqid('XYZ-'));

        // Gestion du fichier pièce jointe
        $cheminPieceJointe = null;
        if ($request->hasFile('piece_jointe')) {
            $cheminPieceJointe = $request->file('piece_jointe')->store('signalements/pieces_jointes');
        }

        // Création du signalement
        $signalement = Signalement::create([
            'type_de_signalement_id' => $request->type_de_signalement_id,
            'description' => $request->description,
            'piece_jointe' => $cheminPieceJointe,
            'code_de_suivi' => $codeDeSuivi,
            'status_id' => $statusNonTraite->id, // Par défaut, mettons "non traité"
        ]);
        return new PostResource(true, 'Status modifié avec succès', $signalement);
    }
}
