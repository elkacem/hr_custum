<?php
//
//use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\Schema;
//
//return new class extends Migration
//{
//    /**
//     * Run the migrations.
//     */
//    public function up(): void
//    {
//        Schema::create('dossiers', function (Blueprint $table) {
//            $table->id();
//
//            // ================== CORE ==================
//            $table->enum('type_dossier', ['national', 'international'])->default('national');
//            $table->dateTime('engagement_date')->nullable();
//
//            // Relations
//            $table->foreignId('id')->constrained('fournisseurs')->cascadeOnDelete();
//            $table->foreignId('id')->nullable()->constrained('directions')->nullOnDelete();
//
//            // Demande Paiement
//            $table->string('demande_number')->nullable();
//            $table->string('condition_paiement')->nullable();
//            $table->string('periode')->nullable();
//
//            // Montants
//            $table->decimal('montant_ht', 15, 2)->nullable();
//            $table->decimal('taux_tva', 8, 2)->nullable();
//            $table->decimal('custom_tva', 8, 2)->nullable();
//            $table->decimal('montant_ttc', 20, 6)->nullable();
//
//            // International
//            $table->decimal('montant_devise', 20, 6)->nullable();
//            $table->enum('monnaie', ['USD', 'EUR'])->nullable();
//            $table->decimal('taux_conversion', 20, 6)->nullable();
//            $table->decimal('montant_ttc_local', 20, 6)->nullable();
//
//            // Rejet
//            $table->boolean('dossier_rejete')->default(false);
//            $table->dateTime('date_envoi')->nullable();
//            $table->text('rejection_reason')->nullable();
//
//            // Workflow
//            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING');
//            $table->unsignedTinyInteger('approval_step')->default(0)
//                ->comment('0 = service, 1 = département, 2 = structure, 3 = validé');
//            $table->unsignedBigInteger('rejected_by')->nullable();
//
//            $table->timestamps();
//        });
//    }
//
//    /**
//     * Reverse the migrations.
//     */
//    public function down(): void
//    {
//        Schema::dropIfExists('dossiers');
//    }
//};


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dossiers', function (Blueprint $table) {
            $table->id();

            // ================== CORE ==================
            $table->enum('type_dossier', ['national', 'international'])->default('national');
            $table->dateTime('engagement_date')->nullable();

            // Relations
            $table->foreignId('fournisseur_id')->constrained('fournisseurs')->cascadeOnDelete();
            $table->foreignId('direction_id')->nullable()->constrained('directions')->nullOnDelete();

            // Demande Paiement
            $table->string('demande_number')->nullable();
            $table->string('condition_paiement')->nullable();
            $table->string('periode')->nullable();

            // ================== NATIONAL ==================
            // Montants base DZD
            $table->decimal('montant_ht', 20, 6)->nullable();
            $table->decimal('taux_tva', 8, 2)->nullable();      // 0, 9, 19, or custom
            $table->decimal('custom_tva', 8, 2)->nullable();

            // Extras nationaux
            $table->decimal('remise_percent', 8, 2)->default(0);
            $table->decimal('taxe_percent', 8, 2)->default(0);
            $table->decimal('timbre_percent', 8, 2)->default(0);

            // TTC DZD (national) OU valeur dérivée si besoin
            $table->decimal('montant_ttc', 20, 6)->nullable();

            // ================== INTERNATIONAL ==================
            // Source de vérité : DEVISE
            $table->decimal('montant_devise', 20, 6)->nullable();
            $table->enum('monnaie', ['USD', 'EUR'])->nullable();
            $table->decimal('taux_conversion', 20, 6)->nullable();  // DZD par unité devise

            // IBS appliqué SUR DEVISE
            $table->decimal('ibs_percent', 8, 2)->default(0);       // % sur devise
            $table->decimal('ibs_devise', 20, 6)->nullable();       // montant IBS en devise
            $table->decimal('montant_devise_net', 20, 6)->nullable(); // devise après IBS

            // Dinars DÉRIVÉS (affichage / stockage)
            $table->decimal('montant_ttc_local', 20, 6)->nullable(); // = devise initiale × taux (TTC dérivé)
            $table->decimal('montant_ht_local', 20, 6)->nullable(); // = devise après IBS × taux (HT dérivé)

            // ================== REJET ==================
            $table->boolean('dossier_rejete')->default(false);
            $table->dateTime('date_envoi')->nullable();
            $table->text('rejection_reason')->nullable();

            // ================== WORKFLOW ==================
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING');
            $table->unsignedTinyInteger('approval_step')->default(0)
                ->comment('0 = service, 1 = département, 2 = structure, 3 = validé');
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            // Indexes utiles
            $table->index(['type_dossier', 'status']);
            $table->index('engagement_date');
            $table->index('monnaie');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dossiers');
    }
};

