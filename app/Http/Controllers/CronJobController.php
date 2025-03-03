<?php

namespace App\Http\Controllers;

use App\Services\CronJobService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CronJobController extends Controller
{
    private CronJobService $cronJobService;

    public function __construct(CronJobService $cronJobService)
    {
        $this->cronJobService = $cronJobService;
    }

    public function getLastLaunchTime(Request $request): JsonResponse
    {
        $name = $request->input('name');
    
        if (!$name) {
            return response()->json(
                $this->getResponse([], false, 'Please provide cron name')
            );
        }
    
        $lastUpdate = $this->cronJobService->getLastUpdate($name);
    
        if (!$lastUpdate) {
            return response()->json(
                $this->getResponse([], false, 'No cron job found with this name')
            );
        }
    
        return response()->json(
            $this->getResponse(['lastUpdate' => $lastUpdate], true)
        );
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