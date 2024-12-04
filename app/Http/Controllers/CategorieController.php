<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index(){
        $categories = Produit::all();
        return response()->json($categories);
    }
    public function store(Request $request){
        $category=Produit::create($request->all());
        return response()->json($category,200);

    }
    public function update(Request $request,$id){
        $category=Produit::find($id);
        $category->update($request->all());
        return response()->json($category,200);
    }
    public function destroy($id){
        Produit::destroy($id);
        return response()->json(null,204);
    }
    public function show($id){
        $category=Produit::find($id);
        return response()->json($category,200);

    }

}
