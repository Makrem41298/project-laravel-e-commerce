<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    public function index(){
        try {
            $commandes=Commande::with('user','produits')->get();
            return response()->json(['commandes'=>$commandes],200);

        }catch (\Exception $e){
            return response()->json(['error'=>$e],500);
        }

    }
    public function store(Request $request){
        try {
            $user_id=auth('users')->id();
            $commande=Commande::create(array_merge($request->only('order_type'),['user_id'=>$user_id,'order_status'=>'pending']));
            foreach ($request->produits as $produit){
                $commande->produits()->attach($produit->id,['quantite'=>$produit['quantite']]);
            }
            return response()->json(['commandes'=>$commande->load('user','produits')],201);

        }catch (\Exception $e){
            return response()->json(['error'=>$e],500);
        }
    }
    public function updateStatusOrder(Request $request,$id)
    {
        try {
            $commande=Commande::find($id);
            $commande->update(['order_status'=>$request->status]);
            return response()->json(['commandes'=>$commande->load('user','produits')],200);

        }catch (\Exception $e){
            return response()->json(['error'=>$e],500);
        }

    }
    public function show($id){
        try {
            $commande=Commande::with('user','produits')->find($id);
            return response()->json(['commandes'=>$commande],200);
        }catch (\Exception $e){
            return response()->json(['error'=>$e],500);
        }
    }
    public function delete($id){
        try {
           $commande= Commande::destroy($id);
            return response()->json(['commandes'=>$commande],200);

        }catch (\Exception $e){
            return response()->json(['error'=>$e],500);

        }

    }

}
