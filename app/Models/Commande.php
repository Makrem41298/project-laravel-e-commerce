<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Commande extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function produits(): BelongsToMany
    {
        return $this->belongsToMany(Produit::class,'passe_commandes')->withPivot('quantite');
    }
    public function commandeable(): MorphTo
    {
        return $this->morphTo();
    }

    public function paiment(): HasOne
    {
        return $this->hasOne(Paiment::class);
    }

}
