<?php

namespace App\Http\Controllers;

use App\Models\Employ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployController extends Controller
{
    /**
     * Afficher les informations d'un employé.
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
     * Mettre à jour les informations d'un employé.
     */
    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:employes,email,' . $id,
            'password' => 'sometimes|string|min:8',
        ]);

        if ($validate->fails()) {
            return response()->json(['success' => false, 'errors' => $validate->errors()], 400);
        }

        try {
            $employ = Employ::findOrFail($id);

            // Mise à jour des champs
            $data = $request->only(['name', 'email']);
            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }

            $employ->update($data);

            return response()->json(['success' => true, 'data' => $employ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }


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

