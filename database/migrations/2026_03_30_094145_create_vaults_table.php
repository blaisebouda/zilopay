<?php

use App\Models\Enums\Currency;
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
        Schema::create('vaults', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->default(DB::raw('gen_random_uuid()'))->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('amount', 20, 8)->default(0);
            $table->string('currency', 8)->default(Currency::XOF->value);
            $table->string('type'); // savings, investment, emergency
            $table->string('status')->default('active'); // active, locked, matured
            $table->timestamp('maturity_date')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('uuid');
            $table->index('user_id');
            $table->index('status');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaults');
    }
};
