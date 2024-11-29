<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Signalement;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;



class SignalementController extends Controller
{

    public function index()
    {
        //get posts
        $signalement = Signalement::latest()->paginate(200);
        $signalement = DB::table('signalements')
            ->join('type_signalements', 'signalements.type_de_signalement_id', '=', 'type_signalements.id')
            ->join('statuses', 'signalements.status_id', '=', 'statuses.id')
            ->select('signalements.*', 'type_signalements.libelle', 'statuses.nom_status')
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

    public function update(Request $request, Signalement $signalement)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'type_de_signalement_id' => 'required|exists:type_signalements,id',
            'description' => 'required|string',
            'piece_jointe' => 'nullable|file|mimes:jpeg,png,pdf,doc,docx,mp4,mkv,avi,mov|max:10240',
            'status_id' => 'nullable|exists:statuses,id',
        ]);


        // Vérification des erreurs de validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Gestion dynamique du statut
        if ($request->status_id) {
            $status = \App\Models\Status::find($request->status_id);
            if (!$status) {
                return response()->json([
                    'message' => 'Le statut spécifié est introuvable.',
                ], 404);
            }
        }

        // Mise à jour du fichier pièce jointe
        if ($request->hasFile('piece_jointe')) {
            // Suppression de l'ancienne pièce jointe si elle existe
            if ($signalement->piece_jointe && Storage::exists($signalement->piece_jointe)) {
                Storage::delete($signalement->piece_jointe);
            }

            // Enregistrement du nouveau fichier
            $cheminPieceJointe = $request->file('piece_jointe')->store('signalements/pieces_jointes');
            $signalement->piece_jointe = $cheminPieceJointe;
        }

        // Mise à jour des autres champs
        $signalement->update([
            'type_de_signalement_id' => $request->type_de_signalement_id,
            'description' => $request->description,
            'status_id' => $request->status_id ?? $signalement->status_id, // Garde le statut actuel si non spécifié
        ]);

        // Retourne une réponse avec le signalement mis à jour
        return new PostResource(true, 'signalement modifié avec succès', $signalement);

    }
}
