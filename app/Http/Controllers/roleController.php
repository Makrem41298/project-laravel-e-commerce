<?php

namespace App\Http\Controllers;
use App\Models\role;

use Illuminate\Http\Request;

class roleController extends Controller
{
    public function index(){
        $role = Role::all();
        return response()->json($role);
    }
    public function store(Request $request){
        $role=Role::create($request->all());
        return response()->json($role,200);

    }

    public function update(Request $request,$id){
        $role=Role::find($id);
        $role->update($request->all());
        return response()->json($role,200);
    }
    public function destroy($id){
        Role::destroy($id);
        return response()->json(null,204);
    }

    public function show($id){
        $role=Role::find($id);
        return response()->json($role,200);

    }
}
