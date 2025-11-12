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
        Schema::table('dossier_rejections', function (Blueprint $table) {
            Schema::table('dossier_rejections', function (Blueprint $table) {
                if (!Schema::hasColumn('dossier_rejections', 'event')) {
                    $table->enum('event', ['REJECT','RESUBMIT'])->default('REJECT')->index()->after('role');
                }
                if (!Schema::hasColumn('dossier_rejections', 'date_envoi')) {
                    $table->date('date_envoi')->nullable()->after('step');
                }
                if (!Schema::hasColumn('dossier_rejections', 'date_retour')) {
                    $table->date('date_retour')->nullable()->after('date_envoi');
                }
                if (!Schema::hasColumn('dossier_rejections', 'resubmitted_by')) {
                    $table->foreignId('resubmitted_by')->nullable()->after('date_retour')
                        ->constrained('admins')->nullOnDelete();
                }
                if (!Schema::hasColumn('dossier_rejections', 'resubmit_note')) {
                    $table->string('resubmit_note', 500)->nullable()->after('resubmitted_by');
                }
                // Make reason nullable to allow RESUBMIT rows without a rejection reason
                try { $table->text('reason')->nullable()->change(); } catch (\Throwable $e) {}
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dossier_rejections', function (Blueprint $table) {
            //
        });
    }
};
