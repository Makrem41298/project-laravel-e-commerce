<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Produit extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function categories(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }
    public function Commandes(): BelongsToMany
    {
        return $this->belongsToMany(Commande::class,'passe_commandes');
    }

}
