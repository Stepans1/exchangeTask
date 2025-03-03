<?php

use App\Http\Controllers\CronJobController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExchangeRateController;

Route::get('/exchange-rates', [ExchangeRateController::class, 'getRates']);

Route::get('/availabel-currencies', [ExchangeRateController::class, 'getAvailabelCurrencies']);

Route::get('/get-last-cron-update', [CronJobController::class, 'getLastLaunchTime']);
