<?php

namespace App\Observers;

use App\Models\Produit;

class ProduitObserver
{
    /**
     * Handle the Produit "created" event.
     */
    public function created(Produit $produit): void
    {


    }
    public function creating(Produit $produit): void
    {
        \Log::info('produit create');
        if($produit->quantite<=0){
            $produit->status='hours_stock';
        }else
            $produit->status='en_stock';


    }
    public function updating(Produit $produit): void
    {
        if($produit->quantite<=0){
            $produit->status='hours_stock';
        }else
            $produit->status='en_stock';


    }

    /**
     * Handle the Produit "updated" event.
     */


    /**
     * Handle the Produit "deleted" event.
     */
    public function deleted(Produit $produit): void
    {
        //
    }

    /**
     * Handle the Produit "restored" event.
     */
    public function restored(Produit $produit): void
    {
        //
    }

    /**
     * Handle the Produit "force deleted" event.
     */
    public function forceDeleted(Produit $produit): void
    {
        //
    }
}
