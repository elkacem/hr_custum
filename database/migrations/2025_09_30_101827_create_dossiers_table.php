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
        Schema::create('dossiers', function (Blueprint $table) {
            $table->id();

            // ================== CORE ==================
            $table->enum('type_dossier', ['national', 'international'])->default('national');
            $table->dateTime('engagement_date')->nullable();

            // Relations
            $table->foreignId('id')->constrained('fournisseurs')->cascadeOnDelete();
            $table->foreignId('id')->nullable()->constrained('directions')->nullOnDelete();

            // Demande Paiement
            $table->string('demande_number')->nullable();
            $table->string('condition_paiement')->nullable();
            $table->string('periode')->nullable();

            // Montants
            $table->decimal('montant_ht', 15, 2)->nullable();
            $table->decimal('taux_tva', 8, 2)->nullable();
            $table->decimal('custom_tva', 8, 2)->nullable();
            $table->decimal('montant_ttc', 20, 6)->nullable();

            // International
            $table->decimal('montant_devise', 20, 6)->nullable();
            $table->enum('monnaie', ['USD', 'EUR'])->nullable();
            $table->decimal('taux_conversion', 20, 6)->nullable();
            $table->decimal('montant_ttc_local', 20, 6)->nullable();

            // Rejet
            $table->boolean('dossier_rejete')->default(false);
            $table->dateTime('date_envoi')->nullable();
            $table->text('rejection_reason')->nullable();

            // Workflow
            $table->enum('status', ['PENDING', 'APPROVED', 'REJECTED'])->default('PENDING');
            $table->unsignedTinyInteger('approval_step')->default(0)
                ->comment('0 = service, 1 = département, 2 = structure, 3 = validé');
            $table->unsignedBigInteger('rejected_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dossiers');
    }
};
