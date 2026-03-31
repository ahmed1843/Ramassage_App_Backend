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
        $user = User::create([
            'name'      => $request->prenom . ' ' . $request->nom,
            'email'     => $request->email,
            'telephone' => $request->telephone,
            'password'  => Hash::make($request->password),
            'role'      => 'citizen'
        ]);

        // 3. On génère le token
        $token = $user->createToken('myapptoken')->plainTextToken;

        return response()->json([
            'message' => 'Utilisateur créé avec succès !',
            'user'    => $user, 
            'token'   => $token
        ], 201);
    }

    // ✅ CONNEXION
    public function login(Request $request) {
        $fields = $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 200);
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
