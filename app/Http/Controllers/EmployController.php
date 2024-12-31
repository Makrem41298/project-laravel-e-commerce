<?php

namespace App\Http\Controllers;

use App\Models\Employ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployController extends Controller
{
    /**
     * Afficher la liste de tous les employés.
     */
    public function index()
    {
        $employ = Employ::all();
        return response()->json(['success' => true, 'data' => $employ], 200);
    }

    /**
     * Afficher les informations d'un employé spécifique.
     */
    public function show($id)
    {
        try {
            $employ = Employ::findOrFail($id);
            return response()->json(['success' => true, 'data' => $employ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 404);
        }
    }

    /**
     * Créer un nouvel employé.
     */
    public function create(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employs,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string|exists:roles,name',
        ]);

        if ($validate->fails()) {
            return response()->json(['success' => false, 'errors' => $validate->errors()], 400);
        }

        try {

            $employ = Employ::create(array_merge($request->except('password',),['password' => bcrypt($request->password)]));
            $employ->assignRole($request->role);
            return response()->json(['success' => true, 'data' => $employ->load('roles')], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Mettre à jour les informations d'un employé existant.
     */
    public function update(Request $request, $id)
    {
        try {
            $employ = Employ::findOrFail($id);

            $validate = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:employs,email,' . $employ->id,
                'password' => 'sometimes|string|min:8',
            ]);

            if ($validate->fails()) {
                return response()->json(['success' => false, 'errors' => $validate->errors()], 400);
            }

            $employ->update(array_merge($request->except('password',),['password' => bcrypt($request->password)]));

            return response()->json(['success' => true, 'data' => $employ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Supprimer un employé.
     */
    public function destroy($id)
    {
        try {
            $employ = Employ::findOrFail($id);
            $employ->delete();

            return response()->json(['success' => true, 'message' => 'Employé supprimé avec succès.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
