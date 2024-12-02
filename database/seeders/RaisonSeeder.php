<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class RaisonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $raisons = [
            'pas objet de procédure judiciaire ou disciplinaire',
            'objet de procédure judiciaire ou disciplinaire ',
        ];

        foreach ($raisons as $raison) {
            // Vérifie si le libellé existe déjà
            if (!DB::table('raisons')->where('libelle', $raison)->exists()) {
                DB::table('raisons')->insert([
                    'libelle' => $raison,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
