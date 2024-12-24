<?php

namespace App\Http\Controllers;

use App\Models\Categorie;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategorieController extends Controller
{
    public function index(){
        try {
            $categories = Categorie::all();
            return response()->json($categories);

        }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()],500);

        }

    }
    public function store(Request $request){
        $validate = Validator::make($request->all(),[
            'nom_categorie' => 'required|unique:categories',

        ]);
        if ($validate->fails()){
            return response()->json($validate->errors(),400);
        }
        try {

            $category=Categorie::create($request->all());
            return response()->json($category,200);

        }catch (\Exception $e){
            return response()->json($e->getMessage(),400);

        }


    }
    public function update(Request $request,$id){
        $validate = Validator::make(array_merge($request->all(),['id_categorie'=>$id]),[
            'nom_categorie' => 'required|unique:categories',
            'id_categorie' => 'exists:categories,id'
        ]);

        if ($validate->fails()){
            return response()->json($validate->errors(),400);
        }
        try {
            $category=Categorie::find($id);
            $category->update($request->all());
            return response()->json($category,200);

        }catch (\Exception $e){
            return response()->json($e->getMessage(),500);
        }

    }
    public function destroy($id){
        $validate = Validator::make(['id_categorie'=>$id],[
            'id_categorie' => 'exists:categories,id'
        ]);

        if ($validate->fails()){
            return response()->json($validate->errors(),400);
        }
        try {
           $category= Categorie::destroy($id);
            return response()->json(['message'=>'categorie deleted','data'=>$category],200);

        }catch (\Exception $e){
            return response()->json($e->getMessage(),500);
        }


    }
    public function show($id){
        $validate = Validator::make(['id_categorie'=>$id],[
            'id_categorie' => 'exists:categories,id'
        ]);

        if ($validate->fails()){
            return response()->json($validate->errors(),400);
        }
        try {

            $category=Categorie::find($id);
            return response()->json($category,200);

        }catch (\Exception $e){
            return response()->json($e->getMessage(),500);
        }


    }

}
