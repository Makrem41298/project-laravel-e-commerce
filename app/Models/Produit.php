<?php

namespace App\Models;

use App\Observers\ProduitObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
#[ObservedBy([ProduitObserver::class])]

    class Produit extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function categories(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }
    public function commandes(): BelongsToMany
    {
        return $this->belongsToMany(Commande::class,'passe_commandes');
    }
    public function images():HasMany
    {
        return $this->hasMany(image::class);

    }
    public function paiement(): HasMany
    {
        return  $this->hasMany(Paiment::class);

    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'avis');
    }



}
