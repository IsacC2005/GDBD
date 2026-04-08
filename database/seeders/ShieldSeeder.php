<?php

namespace Database\Seeders;

use App\Models\User;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ShieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Generando permisos y roles de Shield...');
        $this->command->call('shield:generate', ['--all' => true]);

        $this->command->info('Creando usuario Super Admin...');

        $user = User::create([
            'name' => 'ADMIN',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $user->assignRole(Utils::getSuperAdminName());

        $this->command->info('¡Listo! Login: admin@admin.com | pass: password');
    }
}
