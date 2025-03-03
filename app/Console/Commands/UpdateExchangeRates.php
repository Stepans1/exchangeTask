<?php

namespace App\Console\Commands;

use App\Models\CronJob;
use Illuminate\Console\Command;
use App\Services\ExchangeRateService;

class UpdateExchangeRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update rates';

    /**s
     * Execute the console command.
     */
    public function handle()
    {
        $exchangeRateService = app(ExchangeRateService::class);
        
        $exchangeRateService->updateRates();

        CronJob::create([
            'name' => $this->signature,
            'launched_at' => now(),
        ]);        
    }
}
