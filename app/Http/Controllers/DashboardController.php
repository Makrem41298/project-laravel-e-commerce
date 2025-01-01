<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\Paiment;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function topProduit(Request $request)
    {
        $validated = \Validator::make($request->all(), [
            'date_start' => 'required|date_format:Y-m-d',
            'date_end'   => 'required|date_format:Y-m-d|after_or_equal:date_start',
        ]);
        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        }
        try {
            $topProduit = Produit::withCount(['commandes' => function($query) use ($request) {
                $query->whereBetween('commandes.created_at', [$request->date_start, $request->date_end]);
            }])
                ->orderByDesc('commandes_count') // Trier par le nombre de commandes
                ->get();
            return response()->json($topProduit);
        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()], 500);
        }

    }
    public function totleReviwes(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'date_start' => 'required|date_format:Y-m-d',
            'date_end'   => 'required|date_format:Y-m-d|after_or_equal:date_start',
        ]);
        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()]);
        }
        try {
            $review=Paiment::with('commande',function($query) use ($request){
               $query->whereBetween('commandes.created_at', [$request->date_start, $request->date_end]);
            })
            ->sum('amount');

           return response()->json(['data' => $review]);

        }catch (\Exception $exception){
            return response()->json(['error' => $exception->getMessage()], 500);
        }

    }
}
