<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFormationRequest;
use App\Http\Requests\UpdateFormationRequest;
use Illuminate\Support\Facades\Validator;

class FormationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['list/formation']]);
    }
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/list/formation",
     *     tags={"Formations"},
     *     summary="Get list of formations",
     *     description="Retrieves a list of all formations",
     *     operationId="getFormationList",
     *     @OA\Response(
     *         response=200,
     *         description="List of formations",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(
     *                 property="Liste des formations",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Formation")
     *             ),
     *         ),
     *     ),
     * )
     */
    public function index()
    {
        $Formation = Formation::all();
        return response()->json([
            'status' => 200,
            'Liste des formation' => $Formation,

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    /**
     * @OA\Post(
     *     path="/cloture/formation/{formation}",
     *     tags={"Formations"},
     *     summary="Close a formation",
     *     description="Closes a specific formation",
     *     operationId="closeFormation",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="formation",
     *         in="path",
     *         required=true,
     *         description="ID of the formation to be closed",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Formation closed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="Formation", type="string", example="Cloture/en cours")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Formation not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Formation not found")
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
    public function cloture(Formation $formation)
    {
        $formation->status = "en_cours";
        if ($formation->update()) {
            return response()->json([
                'status' => 200,
                'Formation' => 'Cloture/en cours',

            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/ajout/formation",
     *     tags={"Formations"},
     *     summary="Add a new formation",
     *     description="Creates a new formation",
     *     operationId="addFormation",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nomFormation","description","duree","status"},
     *             @OA\Property(property="nomFormation", type="string", maxLength=255, example="Formation XYZ"),
     *             @OA\Property(property="description", type="string", example="Description of the formation"),
     *             @OA\Property(property="duree", type="integer", example=10),
     *             @OA\Property(property="status", type="string", enum={"en_cours","en_attente"}, example="en_cours"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Formation created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="Formation", type="object", ref="#/components/schemas/Formation")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="field_name", type="array", @OA\Items(type="string"), example={"The field_name field is required."})
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nomFormation' => 'required|string|max:255',
            'description' => 'required|string',
            'duree' => 'required|integer',
            'status' => 'required|in:en_cours,en_attente',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $formation = Formation::create($request->all());
        if ($formation) {
            return response()->json([
                'status' => 200,
                'Formation' => $formation,

            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Formation $formation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Formation $formation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/modifier/formation/{formation}",
     *     tags={"Formations"},
     *     summary="Update a formation",
     *     description="Updates a specific formation",
     *     operationId="updateFormation",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="formation",
     *         in="path",
     *         required=true,
     *         description="ID of the formation to be updated",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nomFormation","description","duree","status"},
     *             @OA\Property(property="nomFormation", type="string", maxLength=255, example="Updated Formation Name"),
     *             @OA\Property(property="description", type="string", example="Updated Description of the formation"),
     *             @OA\Property(property="duree", type="integer", example=20),
     *             @OA\Property(property="status", type="string", enum={"en_cours","en_attente"}, example="en_cours"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Formation updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="Formation", type="object", ref="#/components/schemas/Formation")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="field_name", type="array", @OA\Items(type="string"), example={"The field_name field is required."})
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
    public function update(Request $request, Formation $formation)
    {
        $validator = Validator::make($request->all(), [
            'nomFormation' => 'required|string|max:255',
            'description' => 'required|string',
            'duree' => 'required|integer',
            'status' => 'required|in:en_cours,en_attente',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $formation->update($request->all());
        if ($formation) {
            return response()->json([
                'status' => 200,
                'Formation' => $formation,

            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/supprimer/formation/{formation}",
     *     tags={"Formations"},
     *     summary="Delete a formation",
     *     description="Deletes a specific formation",
     *     operationId="deleteFormation",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="formation",
     *         in="path",
     *         required=true,
     *         description="ID of the formation to be deleted",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Formation deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="Formation", type="string", example="Formation supprimée avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Formation not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Formation not found")
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
    public function delete(Formation $formation)
    {
        $formation->delete();
        if ($formation) {
            return response()->json([
                'status' => 200,
                'Formation' => 'Formation supprimer avec succes',

            ]);
        }
    }
}
