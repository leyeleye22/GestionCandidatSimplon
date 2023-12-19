<?php

namespace App\Http\Controllers;

use App\Models\Candidat;
use App\Models\Candidature;
use App\Http\Requests\StoreCandidatRequest;
use App\Http\Requests\UpdateCandidatRequest;

class CandidatController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/show/candidat",
     *     summary="Get all candidats",
     *     tags={"Candidats"},
     *     @OA\Response(
     *         response=200,
     *         description="List of candidats",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Candidat")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Internal Server Error")
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $candidat = Candidat::all();
            return response()->json([
                'status_code' => 200,
                'data' => $candidat
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function accepeter()
    {
        try {
            $candidat = Candidature::where('accepted', 'isAccept')->get();
            $tableau = array();
            foreach ($candidat as $candidats) {
                $cand = $candidats->user_id;
                $user = Candidat::FindOrFail($cand)->first();
                array_push($tableau, $user);
            }
            // dd(count($tableau));
            return response()->json([
                'status_code' => 200,
                'message' => 'liste des candidature accepter',
                'data' => $tableau
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function refuser()
    {
        try {
            $candidat = Candidature::where('accepted', 'isNotAccept')->get();
            $tableau = array();
            foreach ($candidat as $candidats) {
                $cand = $candidats->user_id;
                $user = Candidat::FindOrFail($cand)->first();
                array_push($tableau, $user);
            }
            // dd(count($tableau));
            return response()->json([
                'status_code' => 200,
                'message' => 'liste des candidature refuser',
                'data' => $tableau
            ]);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCandidatRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Candidat $candidat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Candidat $candidat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCandidatRequest $request, Candidat $candidat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Candidat $candidat)
    {
        //
    }
}
