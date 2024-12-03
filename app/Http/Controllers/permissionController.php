<?php

namespace App\Http\Controllers;
use App\Models\role;

use Illuminate\Http\Request;

class permissionController extends Controller
{
    public function index(){
        $permission = Permission::all();
        return response()->json($permission);
    }
    public function store(Request $request){
        $permission=Permission::create($request->all());
        return response()->json($permission,200);

    }

    public function update(Request $request,$id){
        $permission=Permission::find($id);
        $permission->update($request->all());
        return response()->json($permission,200);
    }
    public function destroy($id){
        Permission::destroy($id);
        return response()->json(null,204);
    }

    public function show($id){
        $permission=Permission::find($id);
        return response()->json($permission);
    }
}
