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
            'name' => 'Phone Test User',
            'email' => 'test@zilopay.com',
            'phone_number' => '22670000001',
        ]);

        $testUser->defaultWallet->credit(500000);
        $testUser->defaultWallet->update(['code' => 'ZP00000001']);
    }
}
