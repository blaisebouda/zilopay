<?php

use App\Models\Enums\CurrencyType;
use App\Models\Enums\CommonStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 20)->default(CurrencyType::FIAT->value);
            $table->string('name', 50);
            $table->char('symbol', 10);
            $table->string('code', 21);
            $table->decimal('rate', 20, 8)->default(0.00000000);
            $table->string('logo', 100)->nullable();
            $table->boolean('default')->default(false);
            $table->string('exchange_from', 6)->default('local');
            $table->string('allowed_wallet_creation', 4)->default('No');
            $table->string('address', 191)->nullable();
            $table->integer('status')->default(CommonStatus::ACTIVE->value);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('currencies');
    }
};
