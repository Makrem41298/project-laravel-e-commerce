<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommandeController extends Controller
{
    /**
     * Récupérer toutes les commandes avec les relations.
     */
    public function index()
    {
        try {
            $commandes = Commande::with('user', 'produits', 'paiment')->get();
            return response()->json(['success' => true, 'data' => $commandes], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Créer une nouvelle commande.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'order_type' => 'required|in:online','in-store',
            'methode' => 'required|in:carte_credit,paypal,virement_bancaire,especes',
            'produits' => 'required|array',
            'produits.*.id' => 'required|exists:produits,id',
            'produits.*.quantite' => 'required|numeric|min:1',
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'errors' => $validation->errors()], 400);
        }

        try {
            $user_id =auth('users')->user()->id;

            $commande = Commande::create([
                'order_type' => $request->order_type,
                'user_id' => $user_id,
                'order_status' => 'pending',
            ]);

            foreach ($request->produits as $produit) {
                $commande->produits()->attach($produit['id'], ['quantite' => $produit['quantite']]);
            }

            $montant = $commande->produits()
                ->selectRaw('prix * passe_commandes.quantite as montant')
                ->get()->sum('montant');

            $commande->paiment()->create([
                'payment_method' => $request->methode,
                'amount' => $montant,
            ]);

            return response()->json(['success' => true, 'data' => $commande->load('user', 'produits', 'paiment')], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Mettre à jour le statut d'une commande.
     */
    public function updateStatusOrder(Request $request, $id)
    {
        $validation = Validator::make(array_merge($request->all(), ['id' => $id]), [
            'id' => 'required|exists:commandes,id',
            'status' => 'required|string|in:pending,completed,canceled',
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'errors' => $validation->errors()], 400);
        }

        try {
            $commande = Commande::findOrFail($id);
            $commande->update(['order_status' => $request->status]);

            return response()->json(['success' => true, 'data' => $commande->load('user', 'produits', 'paiment')], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Afficher les détails d'une commande spécifique.
     */
    public function show($id)
    {
        $validation = Validator::make(['id' => $id], [
            'id' => 'required|exists:commandes,id',
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'errors' => $validation->errors()], 400);
        }

        try {
            $commande = Commande::with('user', 'produits', 'paiment')->findOrFail($id);
            return response()->json(['success' => true, 'data' => $commande], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Supprimer une commande.
     */
    public function delete($id)
    {
        $validation = Validator::make(['id' => $id], [
            'id' => 'required|exists:commandes,id',
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'errors' => $validation->errors()], 400);
        }

        try {
            Commande::destroy($id);
            return response()->json(['success' => true, 'message' => 'Commande supprimée avec succès.'], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
