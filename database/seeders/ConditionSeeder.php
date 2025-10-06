<?php

namespace Database\Seeders;

use App\Models\Condition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $conditions = [
            'Avance',
            'Virement bancaire',
            'Annulation',
            'Espèces',
            'Pénalité',
            'Avoir',
            'Demande de chèque',
            'Régularisation',
            'Chèque',
            'Lettre de credit',
            'Mandat administratif',
            'Traite',
            'Prélèvement',
            'A terme',
            'Contre remboursement',
            'Remboursement',
            'Compensation',
            'Paiement comptant',
            'Paiement différé',
            'Paiement anticipé',
            'Paiement partiel',
            'Paiement échelonné',
            'Paiement en plusieurs fois',
            'Autre',
        ];

        foreach ($conditions as $c) {
            Condition::firstOrCreate(['name' => $c]);
        }
    }
}
