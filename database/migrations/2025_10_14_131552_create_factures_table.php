<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();

            // ===== Relations =====
            $table->foreignId('dossier_id')->constrained('dossiers')->cascadeOnDelete();
            $table->foreignId('direction_id')->nullable()->constrained('directions')->nullOnDelete();
            $table->foreignId('compte_id')->nullable()->constrained('comptes')->nullOnDelete();

            // ===== Core meta =====
            $table->enum('type_dossier', ['national', 'international'])->default('national');
            $table->string('ref_facture')->nullable();
            $table->date('date_facture')->nullable();
            $table->string('bon_commande')->nullable();
            $table->string('numero_contrat')->nullable();
            $table->string('periode')->nullable();
            $table->string('objet')->nullable();

            // ===== NATIONAL (DZD) =====
            $table->decimal('montant_ht', 20, 6)->nullable();
            $table->decimal('taux_tva', 8, 2)->nullable();       // 0, 9, 19, or 'custom'
            $table->decimal('custom_tva', 8, 2)->nullable();     // optional custom TVA

            // Extras nationaux
            $table->decimal('remise_percent', 8, 2)->default(0);
            $table->decimal('taxe_percent',   8, 2)->default(0);
            $table->decimal('timbre_percent', 8, 2)->default(0);

            // TTC national (ou valeur dérivée si besoin)
            $table->decimal('montant_ttc', 20, 6)->nullable();

            // ===== INTERNATIONAL (Devise = source of truth) =====
            $table->enum('monnaie', ['USD', 'EUR'])->nullable();
            $table->decimal('taux_conversion', 20, 6)->nullable(); // DZD / 1 unité devise
            $table->decimal('montant_devise', 20, 6)->nullable();  // devise initiale (TTC logique)

            // IBS appliqué sur DEV ISE
            $table->decimal('ibs_percent', 8, 2)->default(0);      // % sur devise
            $table->decimal('ibs_devise', 20, 6)->nullable();      // montant IBS en devise
            $table->decimal('montant_devise_net', 20, 6)->nullable(); // devise après IBS

            // Dinars dérivés (affichage / stockage)
            $table->decimal('montant_ttc_local', 20, 6)->nullable(); // = devise initiale × taux
            $table->decimal('montant_ht_local',  20, 6)->nullable(); // = devise après IBS × taux

            // ===== Misc =====
            $table->text('observation')->nullable();

            $table->timestamps();

            // ===== Indexes utiles =====
            $table->index(['type_dossier', 'date_facture']);
            $table->index('dossier_id');
            $table->index('monnaie');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
