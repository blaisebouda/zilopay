<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use App\Services\WalletService;
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
        ]);

        $user->defaultWallet->credit(8500);
        $user->defaultWallet->update(['code' => "ZP00000000"]);

        // Test user
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@zilopay.com',
        ]);

        $testUser->defaultWallet->credit(5000);
        $testUser->defaultWallet->update(['code' => "ZP00000001"]);
    }
}
