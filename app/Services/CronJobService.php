<?php

namespace App\Services;

use App\Models\CronJob;

class CronJobService
{
    public function getLastUpdate(string $name): ?string
    {
        $lastCron = CronJob::where('name', $name)
                    ->latest('launched_at')
                    ->first();
    
        if (!$lastCron) {
            return null;
        }
    
        return $lastCron->launched_at->format('d.m.Y') ?? null;
    }
}
