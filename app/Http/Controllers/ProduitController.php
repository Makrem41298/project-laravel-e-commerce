<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ProduitController extends Controller
{

    public function index(){
        try {
            $produits= Produit::all();
            return response()->json(['message'=>$produits],200);

        }catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()]);
        }

    }
    public function store(Request $request){
        $validation=Validator::make($request->all(),[
            'nomProduit'=>'required|String|',
            'prix'=>'required|Decimal',
            'quantite'=>'required|Decimal',
            'description'=>'required',
            'categorie_id'=>'required|exists:categories,id',

        ]);
        if($validation->fails()){
            return response()->json(["message"=>$validation->errors()],404);
        }
        try {
            $produit=Produit::create($request->all());
            return response()->json(["message"=>$produit],200);

        }catch (\Exception $exception){
            return response()->json(["message"=>$exception->getMessage()],404);
        }


    }
    public function update(Request $request,$id){
        $validation=Validator::make(array_merge($request->all(),['id'=>$id]),
            [
                'id'=>'required|exists:produits,id',
                'nomProduit'=>'required|String|',
                'prix'=>'required|Decimal',
                'quantite'=>'required|Decimal',
                'description'=>'required'
            ]

        );
        if($validation->fails()){
            return response()->json(["message"=>$validation->errors()],404);
        }
        $produit=Produit::find($id);
        $produit->update($request->all());
        return response()->json($produit,200);
    }
    public function show($id){
        $validation=Validator::make(['id'=>$id],
            ['id'=>'required|exists:produits,id']
        );
        $produit=Produit::find($id);
        return response()->json($produit,200);

    }
    public function destroy($id){
        $validation=Validator::make(['id'=>$id],
        ['id'=>'required|exists:produits,id']
        );
        if($validation->fails()){
            return response()->json(["message"=>$validation->errors()],404);
        }
        try {
            Produit::destroy($id);
            return response()->json(true,204);

        }catch (\Exception $exception){
            return response()->json(["message"=>$exception->getMessage()],404);
        }


    }


    //
}
