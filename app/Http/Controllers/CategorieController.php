<?php

namespace App\Http\Controllers;

use App\Models\paiement;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index(){
        $categories = paiement::all();
        return response()->json($categories);
    }
    public function store(Request $request){
        $category=paiement::create($request->all());
        return response()->json($category,200);

    }
    public function update(Request $request,$id){
        $category=paiement::find($id);
        $category->update($request->all());
        return response()->json($category,200);
    }
    public function destroy($id){
        paiement::destroy($id);
        return response()->json(null,204);
    }
    public function show($id){
        $category=paiement::find($id);
        return response()->json($category,200);

    }

}
