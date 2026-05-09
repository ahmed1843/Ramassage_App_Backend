<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // --- INSCRIPTION ---
    public function register(Request $request) {
        try {
            $user = User::create([
                'name'      => $request->name ?? 'Utilisateur Sans Nom',
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'role'      => $request->role ?? 'citizen',  // citizen ou driver
                'street'    => $request->street ?? null,     // ← AJOUTÉ : Rue pour les citoyens
                'telephone' => $request->telephone ?? '00000000',
                'push_token' => null,                         // ← AJOUTÉ : Token push
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Utilisateur créé !',
                'user' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken // ← AJOUTÉ pour l'auth
            ], 201);

        } catch (\Exception $e) {
            Log::error("Erreur Inscription : " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // --- CONNEXION ---
    public function login(Request $request) {
        try {
            // 1. On cherche l'utilisateur par son email
            $user = User::where('email', $request->email)->first();

            // 2. On vérifie si l'utilisateur existe et si le mot de passe est correct
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email ou mot de passe incorrect'
                ], 401);
            }

            // 3. Si c'est bon, on renvoie l'utilisateur et son token
            return response()->json([
                'status' => 'success',
                'message' => 'Connexion réussie',
                'user' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken
            ], 200);

        } catch (\Exception $e) {
            Log::error("Erreur Login : " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la connexion',
                'debug' => $e->getMessage()
            ], 500);
        }
    }

    // --- DÉCONNEXION ---
    public function logout(Request $request) {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Déconnecté'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // --- RÉCUPÉRER L'UTILISATEUR CONNECTÉ ---
    public function user(Request $request) {
        return response()->json($request->user());
    }

    // --- METTRE À JOUR LE PROFIL ---
    public function updateProfile(Request $request) {
        try {
            $user = $request->user();
            
            if ($request->has('name')) {
                $user->name = $request->name;
            }
            if ($request->has('telephone')) {
                $user->telephone = $request->telephone;
            }
            if ($request->has('street')) {
                $user->street = $request->street;
            }
            
            $user->save();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Profil mis à jour',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}