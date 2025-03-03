<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CronJob extends Model
{
    protected $table = 'cron_jobs';

    protected $fillable = [
        'name',
        'launched_at',
    ];

    public $timestamps = false;

    protected $casts = [
        'launched_at' => 'datetime',
    ];
}