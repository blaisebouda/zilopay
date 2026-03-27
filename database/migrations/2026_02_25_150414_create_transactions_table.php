<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id'); // Plus sûr pour la volumétrie
            $table->uuid('uuid')->default(DB::raw('gen_random_uuid()'))->unique(); // UUID natif Postgres est plus rapide qu'un string(13)

            // Relations communes
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_method_id')->nullable()->constrained();
            $table->integer('type');

            // Montants (Centralisés)
            $table->string('currency', 8);
            $table->decimal('amount', 20, 8)->default(0);
            $table->decimal('fee_fixed', 20, 8)->default(0);
            $table->decimal('fee_percentage', 20, 8)->default(0);
            $table->decimal('total', 20, 8)->default(0);
            $table->decimal('balance_before', 20, 8)->nullable();
            $table->decimal('balance_after', 20, 8)->nullable();

            $table->integer('status');
            $table->timestamps();

            // Indexes indispensables ici.
            $table->index(['user_id', 'created_at']);
            $table->index(['user_id', 'type', 'created_at']);
            $table->index(['user_id', 'status', 'created_at']);
            $table->index(['type', 'status', 'created_at']);
            $table->index(['payment_method_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
