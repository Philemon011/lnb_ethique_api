<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer l'ID du rôle "Super Admin"
        $role = Role::where('lib', 'Super Admin')->first();

        if ($role) {
            // Vérifier si l'utilisateur admin existe déjà
            if (!User::where('email', 'admin@example.com')->exists()) {
                User::create([
                    'name' => 'Admin',
                    'email' => 'admin@example.com',
                    'password' => Hash::make('password123'), // Remplace par un mot de passe sécurisé
                    'role_id' => $role->id, // Associer l'ID du rôle récupéré
                ]);
            }
        } else {
            echo "Le rôle 'Super Admin' n'existe pas. Ajoutez-le d'abord à la table roles.\n";
        }
    }
}
