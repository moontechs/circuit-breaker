<?php

namespace Moontechs\CircuitBreaker\Strategies;

use Moontechs\CircuitBreaker\CircuitData;
use Moontechs\CircuitBreaker\Drivers\DriverInterface;

class CounterStrategy implements StrategyInterface
{
    private int $limit = 5;

    public function __construct(private DriverInterface $driver)
    {

    }

    public function isAvailable(string $circuitName): bool
    {
        $circuitData = $this->driver->get($circuitName);

        if ($circuitData === null) {
            return true;
        }

        if ($circuitData->failuresCount >= $this->limit) {
            return false;
        }

        return true;
    }

    public function success(string $circuitName): void
    {
        $circuitData = $this->driver->get($circuitName);

        if ($circuitData === null) {
            return;
        }

        $circuitData->failuresCount = 0;
        $this->driver->set($circuitName, $circuitData);
    }

    public function failure(string $circuitName): void
    {
        $circuitData = $this->driver->get($circuitName);

        if ($circuitData === null) {
            $circuitData = new CircuitData();
        }

        $circuitData->failuresCount++;
        $this->driver->set($circuitName, $circuitData);
    }

    public function getFailuresCount(string $circuitName): int
    {
        $circuitData = $this->driver->get($circuitName);

        if ($circuitData === null) {
            return 0;
        }

        return $circuitData->failuresCount;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }
}
