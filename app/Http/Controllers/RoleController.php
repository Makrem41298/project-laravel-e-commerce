<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the roles.
     */
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
      $validate=Validator::make($request->all(),[
          'name' => 'required|unique:roles|max:255',
          'permissions.*' => 'required|exists:permissions,name',

      ]);
      if ($validate->fails()){
          return response()->json(['message'=>$validate->errors()]);
      }

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'employs'
        ]);
        $role->givePermissionTo($request->permission);

        return response()->json(['message' => 'Role created successfully', 'role' => $role], 201);
    }

    /**
     * Display the specified role.
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);
        return response()->json($role);
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, $id)
    {
        $validate=Validator::make($request->all(),[
            'name' => 'sometimes|unique:roles,name,' . $id . '|max:255',
            'permissions.*' => 'sometimes|exists:permissions,name',
        ]);
        if ($validate->fails()){
            return response()->json(['message'=>$validate->errors()]);
        }
        try {
            $role = Role::findOrfail($id);
            if ($request->has('name')) {
                $role->update([
                    'name' => $request->name,
                ]);
            }

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }

            return response()->json(['message' => 'Role updated successfully', 'role' => $role]);

        }catch (\Exception $e){
            return response()->json(['message'=>$e->getMessage()]);
        }



    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }
}
