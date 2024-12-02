<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index(){
        $categories = Categorie::all();
        return response()->json($categories);
    }
    public function store(Request $request){
        $category=Categorie::create($request->all());
        return response()->json($category,200);

    }
    public function update(Request $request,$id){
        $category=Categorie::find($id);
        $category->update($request->all());
        return response()->json($category,200);
    }
    public function destroy($id){
        Categorie::destroy($id);
        return response()->json(null,204);
    }
    public function show($id){
        $category=Categorie::find($id);
        return response()->json($category,200);

    }

}
