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
