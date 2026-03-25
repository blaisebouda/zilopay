<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deposits', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->foreignId('transaction_id')->unique()->constrained()->onDelete('cascade');
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            $table->json('metadata')->nullable();
            $table->json('gateway_response')->nullable();

            // Uniquement le spécifique
            // $table->foreignId('bank_id')->nullable()->constrained();
            // $table->foreignId('file_id')->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
