<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Transaction::factory()
            ->deposit()
            ->pending()
            ->hasDeposit(['wallet_id' => 1])
            ->create(['user_id' => 1]);

        Transaction::factory()
            ->withdrawal()
            ->failed()
            ->hasWithdrawal(['wallet_id' => 1])
            ->create(['user_id' => 1]);

        Transaction::factory()
            ->transfer()
            ->success()
            ->hasTransfer([
                'sender_wallet_id' => 1,
                'receiver_wallet_id' => 2
            ])
            ->create(['user_id' => 1]);

        Transaction::factory()
            ->withdrawal()
            ->pending()
            ->hasWithdrawal(['wallet_id' => 1])
            ->create(['user_id' => 1]);

        Transaction::factory()
            ->transfer()
            ->failed()
            ->hasTransfer([
                'sender_wallet_id' => 1,
                'receiver_wallet_id' => 2
            ])
            ->create(['user_id' => 1]);

        Transaction::factory()
            ->deposit()
            ->failed()
            ->hasDeposit(['wallet_id' => 1])
            ->create(['user_id' => 1]);
    }
}
