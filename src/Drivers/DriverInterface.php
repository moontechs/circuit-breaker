<?php

namespace Moontechs\CircuitBreaker\Drivers;

use Moontechs\CircuitBreaker\CircuitData;

interface DriverInterface
{
    public function get(string $circuitName): ?CircuitData;

    public function set(string $circuitName, CircuitData $circuitData): void;
}
