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
        Schema::table('factures', function (Blueprint $table) {
            $table->unsignedBigInteger('compte_id')->nullable()->after('id');
            $table->unsignedBigInteger('direction_id')->nullable()->after('compte_id');
            $table->string('objet')->nullable()->after('ref_facture');

            $table->foreign('compte_id')->references('id')->on('comptes')->onDelete('set null');
            $table->foreign('direction_id')->references('id')->on('directions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            //
        });
    }
};
