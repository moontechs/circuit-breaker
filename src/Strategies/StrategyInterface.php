<?php

namespace Moontechs\CircuitBreaker\Strategies;

interface StrategyInterface
{
    public function isAvailable(string $circuitName): bool;

    public function success(string $circuitName): void;

    public function failure(string $circuitName): void;

    public function getFailuresCount(string $circuitName): int;
}
