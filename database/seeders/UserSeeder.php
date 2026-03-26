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
        $user = User::factory()->create([
            'name' => 'Zilo Pay User',
            'email' => 'user@zilopay.com',
        ]);

        $user->defaultWallet->credit(8500);
    }
}
