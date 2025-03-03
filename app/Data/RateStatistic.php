<?php

namespace App\Data;

class RateStatistic
{
    public function __construct(
        public float $max,
        public float $min,
        public float $avg,
    ) {}
}
