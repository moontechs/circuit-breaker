<?php

namespace Moontechs\CircuitBreaker;

use Moontechs\CircuitBreaker\Strategies\StrategyInterface;

class CircuitBreaker implements CircuitBreakerInterface
{
    public function __construct(private StrategyInterface $strategy)
    {

    }

    public function isAvailable(string $circuitName): bool
    {
        return $this->strategy->isAvailable($circuitName);
    }

    public function success(string $circuitName): void
    {
        $this->strategy->success($circuitName);
    }

    public function failure(string $circuitName): void
    {
        $this->strategy->failure($circuitName);
    }

    public function getFailuresCount(string $circuitName): int
    {
        return $this->strategy->getFailuresCount($circuitName);
    }
}
