<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Signalement;
use App\Http\Resources\PostResource;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;



class SignalementController extends Controller
{

    public function index()
    {
        //get posts
        $signalement = Signalement::latest()->get();
        $signalement = DB::table('signalements')
            ->join('type_signalements', 'signalements.type_de_signalement_id', '=', 'type_signalements.id')
            ->join('statuses', 'signalements.status_id', '=', 'statuses.id')
            ->select('signalements.*', 'type_signalements.libelle', 'statuses.nom_status')
            ->orderBy('signalements.created_at', 'desc')
            ->get();

        //return collection of signalements as a resource

        return response([
            'success' => true,
            'data' => $signalement,
            'message' => "Liste des signalements",
        ], 200);
    }

    public function getUserSignalements($user_id)
    {
        $user = User::where('id', $user_id)->first();
        if (!$user) {
            return response()->json(['message' => 'Ce user n\'existe pas'], 404);
        }
        //get posts
        $signalement = Signalement::latest()->paginate(200);
        $signalement=DB::table('signalements')
                                ->join('users','signalements.user_id' , '=', 'users.id')
                                ->select('signalements.*')
                                ->where('users.id', $user->id)
                                ->get();
        return response([
            'success' => true,
            'data' => $signalement,
            'message' => "Liste des signalements",
        ], 200);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_de_signalement_id' => 'required|exists:type_signalements,id',
            'description' => 'required|string',
            'date_evenement' => 'required|date',
            'user_id' => 'nullable|exists:users,id',
            'piece_jointe' => 'nullable|file|mimes:jpeg,jpg,png,pdf,doc,docx,mp4,mkv,avi,mov,mp3|max:10240',
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
            $cheminPieceJointe = $request->file('piece_jointe')->store('signalements/pieces_jointes', 'public');
        }

        // Création du signalement
        $signalement = Signalement::create([
            'type_de_signalement_id' => $request->type_de_signalement_id,
            'description' => $request->description,
            'date_evenement' => $request->date_evenement,
            'user_id' => $request->user_id ?? null,
            'piece_jointe' => $cheminPieceJointe,
            'code_de_suivi' => $codeDeSuivi,
            'status_id' => $statusNonTraite->id, // Par défaut, mettons "non traité"
        ]);
        // return new PostResource(true, 'Signalement ajouté avec succès', $signalement);
        return response([
            'success' => true,
            'message' => "le Signalement a été bien enrégistré !",
            'signalement' => $signalement,
            'code_de_suivi' => $codeDeSuivi
        ], 201);
    }

    public function getSignalementByCodeDeSuivi(Request $request)
    {
        // Validation du code de suivi
        $validator = Validator::make($request->all(), [
            'code_de_suivi' => 'required|string|exists:signalements,code_de_suivi',
        ]);

        // Vérification des erreurs de validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Recherche du signalement par code de suivi
        $signalement = Signalement::where('code_de_suivi', $request->code_de_suivi)
            ->join('type_signalements', 'signalements.type_de_signalement_id', '=', 'type_signalements.id')
            ->join('statuses', 'signalements.status_id', '=', 'statuses.id')
            ->first();

        if (!$signalement) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun signalement trouvé avec ce code de suivi.',
            ], 404);
        }

        // Retourner les informations du signalement
        return response()->json([
            'success' => true,
            'message' => 'Signalement récupéré avec succès.',
            'data' => $signalement,
        ], 200);
    }


    public function update(Request $request, Signalement $signalement)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'status_id' => 'nullable|exists:statuses,id',
            'cloturer_verification' => 'nullable|in:oui,non',
            'raison' => 'nullable'
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

        $signalement->update([
            'cloturer_verification' => $request->cloturer_verification ?? $signalement->cloturer_verification,
            'raison' => $request->raison ?? $signalement->raison,
            'status_id' => $request->status_id ?? $signalement->status_id, // Garde le statut actuel si non spécifié
        ]);

        // Retourne une réponse avec le signalement mis à jour
        return response([
            'success' => true,
            'message' => "le  Signalement a été bien modifié !",
        ], 200);
    }

    public function destroy(Signalement $signalement)
    {

        $signalement->delete();

        //return response
        return new PostResource(true, 'Signalement supprimé avec sucess', null);
    }
}
