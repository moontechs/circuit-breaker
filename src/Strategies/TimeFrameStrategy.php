<?php

namespace Moontechs\CircuitBreaker\Strategies;

use Moontechs\CircuitBreaker\CircuitData;
use Moontechs\CircuitBreaker\Drivers\DriverInterface;

class TimeFrameStrategy implements StrategyInterface
{
    private int $limit = 5;

    private int $timeFrame = 5 * 60;

    public function __construct(private DriverInterface $driver)
    {

    }

    public function isAvailable(string $circuitName): bool
    {
        $circuitData = $this->driver->get($circuitName);

        if ($circuitData === null) {
            return true;
        }

        if ($circuitData->startedAt !== null) {
            $diff = time() - $circuitData->startedAt;

            if ($diff > $this->timeFrame) {
                return true;
            }
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
        $circuitData->startedAt = null;
        $this->driver->set($circuitName, $circuitData);
    }

    public function failure(string $circuitName): void
    {
        $circuitData = $this->driver->get($circuitName);

        if ($circuitData === null) {
            $circuitData = new CircuitData();
        }

        if ($circuitData->startedAt !== null) {
            $diff = time() - $circuitData->startedAt;

            if ($diff > $this->timeFrame) {
                $circuitData->failuresCount = 0;
            }
        }

        if ($circuitData->startedAt === null) {
            $circuitData->startedAt = time();
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

    public function setTimeFrame(int $timeFrame): self
    {
        $this->timeFrame = $timeFrame;

        return $this;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }
}
