<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CommandeController extends Controller
{
    /**
     * Récupérer toutes les commandes avec les relations.
     */
    public function index()
    {
        try {
            $commandes = Commande::with('commandeable', 'produits', 'paiment')->get();
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

            'order_type' => 'required|in:online,in-store',
            'methode' => 'required|in:carte_credit,paypal,virement_bancaire,especes',
            'order_status' => 'nullable|in:pending,completed,cancelled',
            'produits' => 'required|array',
            'produits.*.id' => [
                'required',
                Rule::exists('produits', 'id')->where(function ($query) use ($request) {
                    $query->where('quantite', '>=', $request->input('produits.*.quantite')); // Correct comparison logic
                }),
            ],
            'produits.*.quantite' => 'required|numeric|min:1',
            'phone' => 'required|string',
            'address' => [
                Rule::when(get_class($request->userAction) === User::class, [
                    'required',
                    'string'
                ]),
            ],
            'ville' => [
                Rule::when(get_class($request->userAction) === User::class, [
                    'required',
                    'string'
                ]),
            ],
            'code_postal' => [
                Rule::when(get_class($request->userAction) === User::class, [
                    'required',
                    'string'
                ]),
            ]
        ]);

        if ($validation->fails()) {
            return response()->json(['success' => false, 'errors' => $validation->errors()], 400);
        }
        try {

            $commande = Commande::create([
                'order_type' => $request->order_type,
                'order_status' => $request->order_status,
                'commandeable_id' =>$request->userAction->id,
                'commandeable_type' => get_class($request->userAction),
                'address' => $request->address,
                'phone' => $request->phone,
                'ville' => $request->ville,
                'code_postal' => $request->code_postal,
            ]);

            foreach ($request->produits as $produit) {
                $commande->produits()->attach($produit['id'], ['quantite' => $produit['quantite']]);
                $produitcommand=Produit::find($produit['id']);
                    $produitcommand->decrement('quantite', $produit['quantite']);
                $produitcommand->when($produitcommand->quantite<=0,function()use($produitcommand){
                    $produitcommand->update(['status'=>'hours_stock']);
                });


            }

            $montant = $commande->produits()
                ->selectRaw('prix * passe_commandes.quantite as montant')
                ->get()->sum('montant');

            $commande->paiment()->create([
                'payment_method' => $request->methode,
                'amount' => $montant,
            ]);

            return response()->json(['success' => true, 'data' => $commande->load('commandeable', 'produits', 'paiment')], 201);
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
