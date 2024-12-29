<?php

namespace App\Http\Controllers;

use App\Models\Avis;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AvisController extends Controller
{
    /**
     * Afficher tous les avis.
     */
    public function index()
    {
        try {
            $avis = Avis::with('produit', 'user')->get(); // Inclure les relations si nécessaire
            return response()->json(['success' => true, 'data' => $avis], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Créer un nouvel avis.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'produit_id' => 'required|exists:produits,id',
            'avis' => 'required|integer|min:1|max:5',
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'errors' => $validation->errors()], 400);
        }

        try {
            $user_id = auth()->guard('user')->id(); // Obtenir l'utilisateur connecté

            $produit = Produit::find($request->produit_id);
            $produit->users()->attach($user_id, ['avis' => $request->avis]);

            return response()->json([
                'success' => true,
                'data' => $produit->load('avis', 'users'),
            ], 201);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 500);
        }
    }

    /**
     * Mettre à jour un avis existant.
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make(array_merge($request->all(), ['id_avis' => $id]), [
            'id_avis' => 'required|exists:avis,id',
            'avis' => 'required|integer|min:1|max:5',
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'errors' => $validation->errors()], 400);
        }

        try {
            $avis = Avis::findOrFail($id);
            $avis->update($request->only('avis'));

            return response()->json(['success' => true, 'data' => $avis], 200);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 500);
        }
    }

    /**
     * Supprimer un avis.
     */
    public function destroy($id)
    {
        $validation = Validator::make(['id' => $id], [
            'id' => 'required|exists:avis,id',
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'errors' => $validation->errors()], 400);
        }

        try {
            Avis::destroy($id);
            return response()->json(['success' => true, 'message' => 'Avis supprimé avec succès.'], 204);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 500);
        }
    }

    /**
     * Afficher les détails d'un avis.
     */
    public function show($id)
    {
        $validation = Validator::make(['id' => $id], [
            'id' => 'required|exists:avis,id',
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'errors' => $validation->errors()], 400);
        }

        try {
            $avis = Avis::with('produit', 'user')->findOrFail($id);
            return response()->json(['success' => true, 'data' => $avis], 200);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 500);
        }
    }
}
