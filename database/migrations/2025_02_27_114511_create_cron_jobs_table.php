<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLE_NAME = 'cron_jobs';
    private const NAME_COLUMN = 'name';
    private const LAUNCHED_AT_COLUMN = 'launched_at';

    public function up(): void
    {
        if (!Schema::hasTable(self::TABLE_NAME)) {
            Schema::create(self::TABLE_NAME, function (Blueprint $table) {
                $table->id();
                $table->string(self::NAME_COLUMN)->nullable(false);
                $table->timestamp(self::LAUNCHED_AT_COLUMN)->useCurrent()->nullable(false);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable(self::TABLE_NAME)) {
            Schema::dropIfExists(self::TABLE_NAME);
        }
    }
};
