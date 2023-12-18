<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCandidatureRequest;
use App\Http\Requests\UpdateCandidatureRequest;
use App\Models\Formation;

class CandidatureController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['/show/candidature']]);
    }
    /**
     * Display a listing of the resource.
     */
       /**
     * @OA\Get(
     *     path="/show/candidature",
     *     tags={"Candidatures"},
     *     summary="Show list of candidatures",
     *     description="Retrieves a list of all candidatures",
     *     operationId="showCandidatureList",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of candidatures retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="succes", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Liste des candidatures"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Candidature")
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json([
            'succes' => 200,
            'message' => 'liste des candidature',
            'data' => Candidature::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
      /**
     * @OA\Post(
     *     path="/candidater/{formation}",
     *     tags={"Candidatures"},
     *     summary="Apply for a formation",
     *     description="Allows a user to apply for a specific formation",
     *     operationId="applyForFormation",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="formation",
     *         in="path",
     *         required=true,
     *         description="ID of the formation to apply for",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Candidature created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="data", ref="#/components/schemas/Candidature")
     *         )
     *     ),
     *     @OA\Response(
     *         response=300,
     *         description="Error: Candidature already exists",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=300),
     *             @OA\Property(property="message", type="string", example="Candidature already exists")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden: Unable to apply for the formation",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=403),
     *             @OA\Property(property="message", type="string", example="Impossible de candidater")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
    public function store(Request $request, Formation $formation)
    {
        if ($formation->status === "en_cours") {
            return response()->json([
                'status' => 403,
                'message' => 'impossible de candidater'
            ]);
        } else {
            $user = Auth::user()->id;
            $idFormation = $formation->id;
            $candidature = Candidature::where('candidat_id', $user)->where('formation_id', $idFormation)->first();
            if (isset($candidature)) {
                return response()->json([
                    'status' => 300,
                    'message' => 'error'
                ]);
            } else {
                $candidature = new Candidature();
                $candidature->candidat_id = $user;
                $candidature->formation_id = $idFormation;
                if ($candidature->save()) {
                    return response()->json([
                        'status' => 200,
                        'data' => $candidature
                    ]);
                } else {
                    abort(403);
                }
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Candidature $candidature)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Candidature $candidature)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Candidature $candidature)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
        /**
     * @OA\Post(
     *     path="/revoquer/candidature/{candidature}/{formation}",
     *     tags={"Candidatures"},
     *     summary="Revoke a candidature",
     *     description="Revokes a candidature for a specific formation",
     *     operationId="revokeCandidature",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="candidature",
     *         in="path",
     *         required=true,
     *         description="ID of the candidature to be revoked",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="formation",
     *         in="path",
     *         required=true,
     *         description="ID of the formation related to the candidature",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Candidature revoked successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Candidature annulée")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error in revoking candidature",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="integer", example=400),
     *             @OA\Property(property="message", type="string", example="Erreur d'annulation")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Forbidden")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
    public function delete(Candidature $candiadture, Formation $formation)
    {
        // dd($candiadture);
        $user = Auth::user()->id;
        $idFormation = $formation->id;

        if ($candiadture->candidat_id == $user && $candiadture->formation_id == $formation->id) {
            if ($candiadture->delete()) {
                return response()->json([
                    'success' => 200,
                    'message' => 'Candidature annuler'
                ]);
            } else {
                return response()->json([
                    'success' => 400,
                    'message' => 'Erreur annulation'
                ]);
            }
        } else {
            abort(403);
        }
        // $candidature = Candidature::where('candidat_id', $user)->where('formation_id', $idFormation)->first();


    }
}
