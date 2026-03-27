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
        Schema::create('merchant_api_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->constrained()->cascadeOnDelete();
            $table->string('name');                        // 'Production', 'Test'
            $table->string('key')->unique();               // mk_live_xxxx ou mk_test_xxxx
            $table->string('public_key')->unique();        // mk_pub_live_xxx ou mk_pub_test_xxx
            $table->string('secret');                      // hashé en DB
            $table->boolean('is_live')->default(false);    // sandbox vs production
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['key', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_api_keys');
    }
};
