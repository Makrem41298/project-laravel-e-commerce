<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produit extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function avis(): HasMany
    {
        return $this->hasMany(Avis::class);
    }

}
