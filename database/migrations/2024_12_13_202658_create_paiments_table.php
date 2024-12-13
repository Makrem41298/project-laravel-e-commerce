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
        Schema::create('paiments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained('commandes');
            $table->enum('payment_method', ['carte_credit', 'paypal', 'virement_bancaire', 'especes'])->default('carte_credit'); // Enum pour les méthodes de paiement
            $table->decimal('amount', 10, 2); // Montant avec deux décimales
            $table->enum('status', ['en_attente', 'termine', 'echoue'])->default('en_attente'); // Enum pour le statut
            $table->timestamp('paid_at')->nullable(); // Date de finalisation du paiement
            $table->timestamps();

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiments');
    }
};
