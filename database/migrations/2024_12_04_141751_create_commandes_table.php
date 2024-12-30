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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->bigInteger('commandeable_id')->unsigned();
            $table->string('commandeable_type');
            $table->enum('order_type', ['online', 'in-store']);
            $table->enum('order_status', ['pending', 'completed', 'cancelled']);
            $table->string('phone')->nullable();
            $table->string('ville')->nullable();
            $table->integer('code_postal')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
