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
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_id')->constrained()->onDelete('cascade');
            $table->string('ref_facture')->nullable();
            $table->date('date_facture')->nullable();
            $table->string('bon_commande')->nullable();
            $table->string('periode')->nullable();

            $table->decimal('montant_ht', 15, 2)->nullable();
            $table->decimal('taux_tva', 5, 2)->nullable();
            $table->decimal('montant_ttc', 15, 2)->nullable();

            $table->string('monnaie')->nullable();
            $table->decimal('taux_conversion', 10, 4)->nullable();
            $table->decimal('montant_ttc_local', 15, 2)->nullable();

            $table->text('observation')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
