<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('passe_commandes', function (Blueprint $table) {
            $table->id();
            $table->decimal('quantite');!
            $table->foreignId('produit_id')->constrained('produits');
            $table->foreignId('commande_id')->constrained('commandes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passe_commandes');
    }
};
