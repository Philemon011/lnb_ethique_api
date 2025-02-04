<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['lib' => "Super Admin", 'niveau' => 1],
            ['lib' => "Admin", 'niveau' => 2],
            ['lib' => "Utilisateur", 'niveau' => 3],
        ];
        foreach ($roles as $role) {
            Role::firstOrCreate(['lib' => $role['lib']], $role);
        }
    }
}
