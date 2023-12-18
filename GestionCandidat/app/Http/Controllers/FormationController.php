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
