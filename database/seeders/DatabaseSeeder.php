<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear roles
        Role::firstOrCreate(['name' => 'Administrador']);
        Role::firstOrCreate(['name' => 'Usuario']);

        // Crear planes
        Plan::firstOrCreate([
            'name' => 'Básico',
        ], [
            'price' => 9.99,
            'limit_vcards' => 1,
            'features' => json_encode(['vcard_basica' => true]),
        ]);

        Plan::firstOrCreate([
            'name' => 'Pro',
        ], [
            'price' => 19.99,
            'limit_vcards' => 5,
            'features' => json_encode(['vcard_pro' => true, 'modulos_avanzados' => true]),
        ]);

        // Crear templates
        Template::firstOrCreate([
            'identifier' => 'basic',
        ], [
            'name' => 'Plantilla Básica',
            'preview_image' => null,
            'is_premium' => false,
        ]);

        Template::firstOrCreate([
            'identifier' => 'premium',
        ], [
            'name' => 'Plantilla Premium',
            'preview_image' => null,
            'is_premium' => true,
        ]);

        // Crear usuario administrador
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('Administrador');

        // Crear usuario normal
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'plan_id' => 1, // Básico
        ]);
        $user->assignRole('Usuario');
    }
}
