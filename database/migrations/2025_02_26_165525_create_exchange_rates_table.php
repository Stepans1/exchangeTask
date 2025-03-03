<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    private const TABLE_NAME = 'exchange_rates';
    private const BASE_CURRENCY_COLUMN = 'base_currency';
    private const TARGET_CURRENCY_COLUMN = 'target_currency';
    private const RATE_COLUMN = 'rate';

    public function up()
    {
        if (!Schema::hasTable(self::TABLE_NAME)) {
            Schema::create(self::TABLE_NAME, function (Blueprint $table) {
                $table->id();
                $table->string(self::BASE_CURRENCY_COLUMN, 3)->nullable(false);
                $table->string(self::TARGET_CURRENCY_COLUMN, 3)->nullable(false);
                $table->decimal(self::RATE_COLUMN, 10, 4)->nullable(false);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable(self::TABLE_NAME)) {
            Schema::dropIfExists(self::TABLE_NAME);
        }
    }
};
