<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $statuses = [
            'Non traité',
            'En cours',
            'Résolu',
            'Rejeté',
        ];

        foreach ($statuses as $status) {
            // Vérifie si le libellé existe déjà
            if (!DB::table('statuses')->where('nom_status', $status)->exists()) {
                DB::table('statuses')->insert([
                    'nom_status' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
