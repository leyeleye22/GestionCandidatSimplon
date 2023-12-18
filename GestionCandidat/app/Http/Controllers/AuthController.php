<?php

namespace App\Http\Controllers;


use App\Models\Candidat;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:candidats',
            'telephone' => 'required|string|max:255|unique:candidats',
            'dateNaissance' => 'required|date',
            'adresse' => 'required|string|max:255',
            'formationDesiree' => 'required|string|max:255',
            'accepted' => 'required|in:isAccept,isNotAccept',
            'role' => 'required|in:candidat,admin',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        $user = Candidat::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'dateNaissance' => $request->dateNaissance,
            'adresse' => $request->adresse,
            'formationDesiree' => $request->formationDesiree,
            'accepted' => $request->accepted,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);
        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
