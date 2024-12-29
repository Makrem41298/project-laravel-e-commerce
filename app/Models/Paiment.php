<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Paiment extends Model
{
    use HasFactory;
    protected $guarded=[];


    public function commande():BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

}
