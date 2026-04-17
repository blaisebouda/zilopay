<?php

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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('currency', 8);

            $table->decimal('balance', 20, 8)->default(0.00000000);
            $table->boolean('is_default')->default(false);
            $table->string('code')->unique();
            $table->integer('status')->default(LockActiveStatus::ACTIVE->value);

            $table->unique(['user_id', 'currency']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
