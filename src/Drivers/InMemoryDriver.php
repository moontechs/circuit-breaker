<?php

namespace Moontechs\CircuitBreaker\Drivers;

use Moontechs\CircuitBreaker\CircuitData;

class InMemoryDriver implements DriverInterface
{
    private array $circuits = [];

    public function get(string $circuitName): ?CircuitData
    {
        if (isset($this->circuits[$circuitName])) {
            return $this->circuits[$circuitName];
        }

        return null;
    }

    public function set(string $circuitName, CircuitData $circuitData): void
    {
        $this->circuits[$circuitName] = $circuitData;
    }
}
