<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // --- INSCRIPTION ---
    public function register(Request $request) {
        try {
            $user = User::create([
                'name'      => $request->name ?? 'Utilisateur Sans Nom',
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                // ✅ FIX SÉCURITÉ : on n'accepte que 'citizen' ou 'driver' à l'inscription.
                'role'      => in_array($request->role, ['citizen', 'driver']) ? $request->role : 'citizen',
                'street'    => $request->street ?? null,
                'telephone' => $request->telephone ?? null,
                'push_token' => null,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Utilisateur créé !',
                'user' => $user,
                'token' => $user->createToken('auth_token')->plainTextToken
            ], 201);

        } catch (\Exception $e) {
            Log::error("Erreur Inscription : " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Impossible de créer le compte'
            ], 500);
        }
    }

    // --- CONNEXION ---
    public function login(Request $request) {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email ou mot de passe incorrect'
                ], 401);
            }

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
                'message' => 'Erreur lors de la connexion'
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
                'message' => 'Erreur lors de la déconnexion'
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
            // ✅ 'role' volontairement non modifiable via cette route.

            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Profil mis à jour',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Impossible de mettre à jour le profil'
            ], 500);
        }
    }

    // --- SUPPRIMER LE COMPTE ---
    // ✅ NOUVELLE MÉTHODE : la route DELETE /user/delete existait déjà dans routes/api.php
    // mais pointait vers une méthode inexistante, ce qui aurait provoqué une erreur 500
    // si le bouton "Supprimer mon compte" avait été utilisé.
    public function deleteAccount(Request $request) {
        try {
            $user = $request->user();

            DB::transaction(function () use ($user) {
                // Révoque tous les tokens d'authentification actifs de cet utilisateur
                $user->tokens()->delete();

                // Anonymise les signalements plutôt que de les supprimer :
                // l'historique reste utile pour les statistiques globales de la zone,
                // sans conserver de lien identifiable vers le compte supprimé.
                if (class_exists(\App\Models\Report::class)) {
                    \App\Models\Report::where('user_id', $user->id)->update(['user_id' => null]);
                }

                $user->delete();
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Compte supprimé avec succès'
            ], 200);

        } catch (\Exception $e) {
            Log::error("Erreur suppression compte : " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Impossible de supprimer le compte actuellement'
            ], 500);
        }
    }
}