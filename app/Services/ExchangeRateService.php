<?php

namespace App\Services;

use App\Data\RateStatistic;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ExchangeRate;
use App\Repositories\ExchangeRateRepository;
use Illuminate\Http\Client\Response;

class ExchangeRateService
{
    private const API_URL = 'https://anyapi.io/api/v1/exchange/convert';
    public const CURRENCIES = ['EUR', 'GBP', 'USD', 'AUD'];
    private ExchangeRateRepository $repo;

    public function __construct(ExchangeRateRepository $exchangeRateRepository)
    {
        $this->repo = $exchangeRateRepository;
    }

    public function updateRates(): void
    {
        $apiKey = env('ANYAPI_KEY');

        foreach (self::CURRENCIES as $base) {
            foreach (self::CURRENCIES as $target) {
                if ($base !== $target) {
                    try {
                        $response = $this->requestRates($apiKey, $base, $target);

                        if ($response->successful()) {
                            $rate = $response->json()['rate'] ?? null;

                            if ($rate) {
                                ExchangeRate::create([
                                    'base_currency' => $base,
                                    'target_currency' => $target,
                                    'rate' => $rate,
                                ]);
                            }
                        } else {
                            Log::error("Error while requesting {$base} to {$target}.", [
                                'status' => $response->status(),
                                'body' => $response->body(),
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error("Error while requesting {$base} to {$target}", [
                            'status' => $response->status(),
                            'body' => $response->body(),
                        ]);
                    }
                }

                usleep(microseconds: 900000);
            }
        }
    } 
    
    public function getRates(string $base, string $target, int $page = 1): ?array
    {
        $rates = $this->repo->getRatesByPage($base, $target, $page);

        if ($rates->isEmpty()) {
            return null;
        }

        $rates->getCollection()->transform(function (ExchangeRate $rate): ExchangeRate {
            $rate->formatted_created_at = $rate->created_at->format('d.m.Y');
            return $rate;
        });

        return [
            'rates' => $rates->items(),
            'statistic' => $this->getStatistic($base, $target),
            'pagination' => [
                'lastPage' => $rates->lastPage(),
            ],
        ];
    }

    private function getStatistic(string $base, string $target): ?RateStatistic
    {
      $rates = ExchangeRate::where('base_currency', $base)
          ->where('target_currency', $target)
          ->pluck('rate');

      if ($rates->isEmpty()) {
          return null;
      }
      
      return new RateStatistic(
        round($rates->max(), 5),
        round($rates->min(), 5),
        round($rates->avg(), 5),
        );
    }

    private function requestRates(string $apiKey, string $base, string $target): Response
    {
        return Http::get(self::API_URL, [
            'apiKey' => $apiKey,
            'base' => $base,
            'to' => $target,
            'amount' => 1
        ]);
    }
}
