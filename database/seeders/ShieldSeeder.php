<?php

namespace Database\Seeders;

use App\Models\User;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->command->info('Generando permisos y roles de Shield...');
        $this->command->call('shield:generate', [
            '--all' => true,
            '--panel' => 'admin',
            '--no-interaction' => true,
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->command->info('Creando usuario Super Admin...');

        $user = User::firstOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name' => 'ADMIN',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $user->syncRoles([Utils::getSuperAdminName()]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->command->info('¡Listo! Login: admin@admin.com | pass: password');
    }
}
