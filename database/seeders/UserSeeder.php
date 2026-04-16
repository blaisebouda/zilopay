<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Main user
        $user = User::factory()->create([
            'name' => 'Zilo Pay User',
            'email' => 'user@zilopay.com',
            'phone_number' => '22670000000',
        ]);

        $user->defaultWallet->credit(888500);
        $user->defaultWallet->update(['code' => 'ZP00000000']);

        // Test user
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@zilopay.com',
            'phone_number' => '22670000001',
        ]);

        $testUser->defaultWallet->credit(500000);
        $testUser->defaultWallet->update(['code' => 'ZP00000001']);

        // Admin user
        $adminUser = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@zilopay.com',
            'phone_number' => '22670707070',
            'role' => 'admin',
        ]);

        $adminUser->defaultWallet->credit(1000000);
        $adminUser->defaultWallet->update(['code' => 'ZP00000002']);
    }
}
