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
    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Authentication"},
     *     summary="Logs in a user",
     *     description="Authenticates a user and returns a JWT token upon successful login",
     *     operationId="login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *             @OA\Property(property="authorization", type="object",
     *                 @OA\Property(property="token", type="string", example="generated-token"),
     *                 @OA\Property(property="type", type="string", example="bearer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=403),
     *             @OA\Property(property="error", type="string", example="erreur de connexion")
     *         )
     *     )
     * )
     */
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
        if ($user) {
            return response()->json([
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);
        } else {
            return response()->json([
                'status' => 403,
                'error' => 'erreur de connexion',
            ]);
        }
    }

    /**
     * @OA\Post(
     *     path="/register",
     *     tags={"Authentication"},
     *     summary="Register a new user",
     *     description="Creates a new user account",
     *     operationId="register",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nom","prenom","email","telephone","dateNaissance","adresse","formationDesiree","accepted","role","password"},
     *             @OA\Property(property="nom", type="string", maxLength=255, example="John"),
     *             @OA\Property(property="prenom", type="string", maxLength=255, example="Doe"),
     *             @OA\Property(property="email", type="string", format="email", maxLength=255, example="john@example.com"),
     *             @OA\Property(property="telephone", type="string", maxLength=12, example="770123456"),
     *             @OA\Property(property="dateNaissance", type="string", format="date", example="1990-01-01"),
     *             @OA\Property(property="adresse", type="string", maxLength=255, example="123 Street, City"),
     *             @OA\Property(property="formationDesiree", type="string", maxLength=255, example="Computer Science"),
     *             @OA\Property(property="accepted", type="string", enum={"isAccept","isNotAccept"}, example="isAccept"),
     *             @OA\Property(property="role", type="string", enum={"candidat","admin"}, example="candidat"),
     *             @OA\Property(property="password", type="string", minLength=6, example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User created successfully"),
     *             @OA\Property(property="user", type="object", ref="#/components/schemas/Candidat")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="field_name", type="array", @OA\Items(type="string"), example={"The field_name field is required."})
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:candidats',
            'telephone' => 'required|string|max:12|unique:candidats|regex:/^(77|78|70|75|76)[0-9]{7}$/',
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
    /**
     * @OA\Post(
     *     path="/logout",
     *     tags={"Authentication"},
     *     summary="Logout a user",
     *     description="Logs out the currently authenticated user",
     *     operationId="logout",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully logged out",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Successfully logged out")
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
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }
    /**
     * @OA\Post(
     *     path="/refresh",
     *     tags={"Authentication"},
     *     summary="Refresh user's access token",
     *     description="Refreshes the user's access token",
     *     operationId="refresh",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Token refreshed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *             @OA\Property(property="authorisation", type="object",
     *                 @OA\Property(property="token", type="string", example="newly-generated-token"),
     *                 @OA\Property(property="type", type="string", example="bearer")
     *             )
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
