<?php

use App\Models\Enums\MerchantTransactionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('merchant_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->constrained();
            $table->uuid()->default(DB::raw('gen_random_uuid()'))->unique();
            $table->string("phone_number", 25)->nullable();

            $table->decimal('gross_amount', 20, 8);     // 1000 — ce que le user a payé
            $table->decimal('platform_fee', 20, 8);     // 50  — la commission
            $table->decimal('net_amount', 20, 8);       // 950 — ce que reçoit le marchand

            $table->integer('status')->default(MerchantTransactionStatus::PENDING->value);
            $table->timestamp('settled_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['merchant_id', 'status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_transactions');
    }
};
