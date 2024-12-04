<?php

namespace App\Http\Controllers;

use App\Models\avis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AvisController extends Controller
{
    public function index(){
        $avis = Avis::all();
        return response()->json($avis);
    }

    public function store(Request $request){
        $validation=Validator::make($request->all(),[
            'nomProduit'=>'required|String',
            'date_publication'=>'required|date',
            'description'=>'required',
        'user_id'=>'required|exists:users,id',

        ]);
        if($validation->fails()){
            return response()->json(["message"=>$validation->errors()],404);
        }
        try {

            $avis =Avis::create($request->all());
            return response()->json(["message"=>$avis],200);

        }catch (\Exception $exception){
            return response()->json(["message"=>$exception->getMessage()],404);
        }


    }

    public function update(Request $request,$id){
        try{
            $avis=Avis::find($id);
            $avis->update($request->all());
            return response()->json(["message"=>$avis],200);
        }
        catch (\Exception $exception){

            return response()->json(["message"=>$exception->getMessage()],404);
        }

    }
    public function destroy($id){
        $validation=Validator::make(['id'=>$id],[

            'id'=>'required|exists:avis,id',
        ]);
        if($validation->fails()){
            return response()->json(["message"=>$validation->errors()],404);
        }
        try{
            Avis::destroy($id);

            return response()->json(true,204);
        }
        catch(\Exception $exception){
            return response()->json(["message"=>$exception->getMessage()],404);

        }

    }
    public function show($id){
        $validation=Validator::make(['id'=>$id],[

            'id'=>'required|exists:avis,id',
        ]);
        if($validation->fails()){
            return response()->json(["message"=>$validation->errors()],404);
        }
        try{
            $avis=Avis::find($id);
            return response()->json($avis,200);
        }
        catch(\Exception $exception){
            return response()->json(["message"=>$exception->getMessage()],404);
        }


    }

}
