<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // INSCRIPTION (Register)
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'role' => 'citizen'
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    // CONNEXION (Login)
    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Vérifier l'email
        $user = User::where('email', $fields['email'])->first();

        // Vérifier le mot de passe
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 200);
    }

    // DÉCONNEXION (Logout)
    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Déconnecté']);

    }
// MISE À JOUR DU PROFIL (Update)
public function updateProfile(Request $request) {
    $user = $request->user(); // On récupère l'utilisateur connecté via le token

    $fields = $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'email' => 'sometimes|required|string|email|unique:users,email,' . $user->id,
        // On peut aussi ajouter le quartier si tu as la colonne en BDD
        'quartier' => 'nullable|string' 
    ]);

    // On met à jour les données
    $user->update($fields);

    return response()->json([
        'message' => 'Profil mis à jour avec succès',
        'user' => $user
    ], 200);
}

}

