<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Afficher la liste des utilisateurs.
     */
    public function index()
    {
        $users = User::all();
        return response()->json(['success' => true, 'data' => $users], 200);
    }

    /**
     * Afficher les informations d'un utilisateur spécifique.
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json(['success' => true, 'data' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 404);
        }
    }

    /**


    /**
     * Mettre à jour les informations d'un utilisateur.
     */
    public function update(Request $request, $id)
    {
        // Validation des données envoyées dans la requête
        $validate = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8',
        ]);

        if ($validate->fails()) {
            return response()->json(['success' => false, 'errors' => $validate->errors()], 400);
        }

        try {
            $user = User::findOrFail($id);

            // Mise à jour des informations de l'utilisateur
            $user->update([
                'name' => $request->name ?? $user->name,
                'email' => $request->email ?? $user->email,
                'password' => $request->password ? bcrypt($request->password) : $user->password,
            ]);

            return response()->json(['success' => true, 'data' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Supprimer un utilisateur.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json(['success' => true, 'message' => 'Utilisateur supprimé avec succès.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
