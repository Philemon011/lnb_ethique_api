<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeDeSignalementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $types = [
            'Corruption',
            'Fraude',
            'Harcèlement',
            'Discrimination',
            'Atteintes aux droits humains',
            'Crimes',
            'Blanchiment de capitaux',
            'Suggestions',
            'Propositions d\'amélioration',
        ];

        foreach ($types as $type) {
            // Vérifie si le libellé existe déjà
            if (!DB::table('type_signalements')->where('libelle', $type)->exists()) {
                DB::table('type_signalements')->insert([
                    'libelle' => $type,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
