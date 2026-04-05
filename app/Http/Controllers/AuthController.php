<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // ✅ INSCRIPTION
    public function register(Request $request) {
        // 1. On valide les données
        $validator = Validator::make($request->all(), [
            'prenom'    => 'required|string|max:255',
            'nom'       => 'required|string|max:255',
            'telephone' => 'required|string',
            'email'     => 'required|string|email|unique:users,email',
            'password'  => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 2. On crée l'utilisateur
  // 2. On crée l'utilisateur
$user = User::create([
    'name'      => $request->prenom . ' ' . $request->nom,
    'email'     => $request->email,
    'telephone' => $request->telephone,
    'password'  => Hash::make($request->password),
    // 'role'   => 'citizen'  <-- ❌ SUPPRIME CETTE LIGNE si tu n'as pas de colonne 'role'
]);

        // 3. On génère le token
        $token = $user->createToken('myapptoken')->plainTextToken;

        return response()->json([
            'message' => 'Utilisateur créé avec succès !',
            'user'    => $user, 
            'token'   => $token
        ], 201);
    }

public function login(Request $request) {
    $fields = $request->validate([
        'email'    => 'required|string|email',
        'password' => 'required|string'
    ]);

    $user = User::where('email', $fields['email'])->first();

    if(!$user || !Hash::check($fields['password'], $user->password)) {
        // Si c'est une requête API (mobile), on rend du JSON
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }
        // Si c'est le formulaire web, on revient en arrière avec une erreur
        return back()->withErrors(['email' => 'Identifiants incorrects']);
    }

    // On connecte l'utilisateur pour la session Web
    auth()->login($user);

    // 🛡️ On génère le token pour l'éventuelle partie mobile/API
    $token = $user->createToken('myapptoken')->plainTextToken;

    // --- LOGIQUE DE REDIRECTION ICI ---
    if (!$request->wantsJson()) {
        if ($user->role === 'admin') {
            return redirect('/admin'); // Envoie l'admin vers le tableau vert
        }
        return redirect('/profil'); // Envoie l'utilisateur normal vers son profil
    }

    // Réponse JSON pour l'application mobile
    return response()->json([
        'user'  => $user, 
        'token' => $token,
        'role'  => $user->role
    ], 200);
}



    // ✅ MISE À JOUR DU PROFIL
    public function updateProfile(Request $request) {
        $user = $request->user();

        $fields = $request->validate([
            'name'     => 'sometimes|required|string|max:255',
            'email'    => 'sometimes|required|string|email|unique:users,email,' . $user->id,
            'quartier' => 'nullable|string' 
        ]);

        $user->update($fields);

        return response()->json([
            'message' => 'Profil mis à jour avec succès',
            'user'    => $user
        ], 200);
    }

    // ✅ DÉCONNEXION
    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Déconnecté']);
    }
}
