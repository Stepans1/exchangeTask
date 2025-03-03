<?php

namespace App\Repositories;

use App\Models\ExchangeRate;
use Illuminate\Pagination\LengthAwarePaginator;

class ExchangeRateRepository
{
    private const ITEMS_PER_PAGE = 10;

    public function getRatesByPage(string $base, string $target, int $page): ?LengthAwarePaginator
    {
        return ExchangeRate::where('base_currency', $base)
            ->where('target_currency', $target)
            ->orderBy('created_at', 'desc')
            ->paginate(self::ITEMS_PER_PAGE, ['*'], 'page', $page);
    }
}