<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaiementController extends Controller
{


    public function index()
    {
        $paiements = Paiement::all();
        return response()->json($paiements, 200);
    }

    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'montant' => 'required|numeric',
                'methode' => 'required|string|max:255',
                'statut' => 'required|string|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }


            $paiement = Paiement::create($request->all());
            return response()->json($paiement, 201);

        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the paiement', 'details' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $paiement = Paiement::find($id);

        if (!$paiement) {
            return response()->json(['error' => 'Paiement not found'], 404);
        }

        return response()->json($paiement, 200);
    }


    public function update(Request $request, $id)
    {
        try {

            $paiement = Paiement::find($id);

            if (!$paiement) {
                return response()->json(['error' => 'Paiement not found'], 404);
            }


            $validator = Validator::make($request->all(), [
                'montant' => 'sometimes|required|numeric',
                'methode' => 'sometimes|required|string|max:255',
                'statut' => 'sometimes|required|string|max:50',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }


            $paiement->update($request->all());
            return response()->json($paiement, 200);

        } catch (\Exception $e) {

            return response()->json(['error' => 'An error occurred while updating the paiement', 'details' => $e->getMessage()], 500);
        }
    }



    public function destroy($id)
    {
        try {
            $paiement = Paiement::find($id);

            if (!$paiement) {
                return response()->json(['error' => 'Paiement not found'], 404);
            }

            $paiement->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the paiement'], 500);
        }
    }}
