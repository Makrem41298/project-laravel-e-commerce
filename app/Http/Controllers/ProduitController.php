<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProduitController extends Controller
{
    /**
     * Récupérer tous les produits.
     */
    public function index()
    {
        try {
            $produits = Produit::all();
            return response()->json(['success' => true, 'data' => $produits], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Créer un nouveau produit.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'nomProduit' => 'required|string|max:255|unique:produits',
            'prix' => 'required|numeric|min:0',
            'quantite' => 'required|numeric|min:0',
            'description' => 'required|string',
            'categorie_id' => 'required|exists:categories,id',
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'errors' => $validation->errors()], 400);
        }

        try {
            $produit = Produit::create($request->all());
            return response()->json(['success' => true, 'data' => $produit], 201);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 500);
        }
    }

    /**
     * Mettre à jour un produit existant.
     */
    public function update(Request $request, $id)
    {
        $validation = Validator::make(array_merge($request->all(), ['id' => $id]), [
            'id' => 'required|exists:produits,id',
            'nomProduit' => 'sometimes|string|max:255',
            'prix' => 'sometimes|numeric|min:0',
            'quantite' => 'sometimes|numeric|min:0',
            'description' => 'sometimes|string',
            'categorie_id' => 'sometimes|exists:categories,id',
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'errors' => $validation->errors()], 400);
        }

        try {
            $produit = Produit::findOrFail($id);
            $produit->update($request->all());
            return response()->json(['success' => true, 'data' => $produit], 200);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 500);
        }
    }

    /**
     * Afficher les détails d'un produit spécifique.
     */
    public function show($id)
    {
        $validation = Validator::make(['id' => $id], [
            'id' => 'required|exists:produits,id',
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'errors' => $validation->errors()], 400);
        }

        try {
            $produit = Produit::findOrFail($id);
            return response()->json(['success' => true, 'data' => $produit], 200);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 500);
        }
    }

    /**
     * Supprimer un produit.
     */
    public function destroy($id)
    {
        $validation = Validator::make(['id' => $id], [
            'id' => 'required|exists:produits,id',
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'errors' => $validation->errors()], 400);
        }

        try {

            return response()->json(['success' => true, 'message' => 'Produit supprimé avec succès.'], 204);
        } catch (\Exception $exception) {
            return response()->json(['success' => false, 'error' => $exception->getMessage()], 500);
        }
    }
}
