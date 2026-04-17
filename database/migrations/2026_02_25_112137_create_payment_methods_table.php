<?php

use App\Models\Enums\Currency;
use App\Models\Enums\LockActiveStatus;
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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('country', 8);
            $table->string('name', 100);
            $table->string('logo', 255);
            $table->string('code', 50)->unique();
            $table->string('type', 50)->comment('mobile_money, card, bank');
            $table->decimal('min_amount', 15, 2);
            $table->decimal('max_amount', 15, 2);
            $table->decimal('fee_percent', 5, 2)->default(0);
            $table->decimal('fee_fixed', 15, 2)->default(0);
            $table->string('currency', 10)->default(Currency::XOF->value);
            $table->integer('status')->default(LockActiveStatus::ACTIVE->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
