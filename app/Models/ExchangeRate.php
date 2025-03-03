<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeRate extends Model
{
    protected $table = 'exchange_rates';

    protected $fillable = [
        'base_currency',
        'target_currency',
        'rate',
    ];

    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at->format('d.m.Y');
    }
}