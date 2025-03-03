<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ExchangeRateService;
use Illuminate\Http\JsonResponse;

class ExchangeRateController extends Controller
{
    private ExchangeRateService $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService)
    {
        $this->exchangeRateService = $exchangeRateService;
    }

    public function getRates(Request $request): JsonResponse
    {
        try {
            $base = strtoupper($request->input('base'));
            $target = strtoupper($request->input('target'));
            $selectedPage = $request->input('selectedPage');

            $rates = $this->exchangeRateService->getRates($base, $target, $selectedPage);

            if (!$rates) {
                return response()->json($this->getResponse([], false));
            }

            return response()->json($this->getResponse($rates));
        } catch (\Exception $e) {
           return response()->json($this->getResponse([], false));
        }
    }

    public function getAvailabelCurrencies(Request $request): JsonResponse
    {
        return response()->json($this->getResponse($this->exchangeRateService::CURRENCIES));
    }

    private function getResponse(array $data, bool $isSuccess = true, string $message = ''): array
    {
        return [
            'data' => $data,
            'message' => $message,
            'isSuccess' => $isSuccess,
        ];
    }
}
