<?php

namespace App\Services\Transactions\Contracts;

use App\Models\Transaction;
use App\Models\User;

interface TransactionServiceInterface
{
    /**
     * Create a new transaction
     *
     * @param  User  $user  The user initiating the transaction
     * @param  array  $data  Transaction data
     * @return Transaction The created transaction
     *
     * @throws \InvalidArgumentException When validation fails
     * @throws \Exception When transaction fails
     */
    public function create(User $user, array $data): Transaction;

    /**
     * Confirm a pending transaction
     *
     * @param  string  $reference  Unique transaction reference
     * @param  array  $gatewayData  Gateway response data
     * @return Transaction The confirmed transaction
     *
     * @throws \Exception When confirmation fails
     */
    public function confirm(string $reference, array $gatewayData): Transaction;

    /**
     * Cancel a pending transaction
     *
     * @param  string  $reference  Unique transaction reference
     * @param  string  $reason  Cancellation reason
     * @return Transaction The cancelled transaction
     *
     * @throws \Exception When cancellation fails
     */
    public function cancel(string $reference, string $reason): Transaction;
}
