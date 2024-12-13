<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Commande extends Model
{
    use HasFactory;
    public function produits(): BelongsToMany
    {
        return $this->belongsToMany(Produit::class,'passe_commandes');
    }
    public function user(): BelongsTo
    {
      return $this->belongsTo(User::class);

    }

}
