<?php

namespace Moontechs\CircuitBreaker;

interface CircuitBreakerInterface
{
    public function isAvailable(string $circuitName): bool;

    public function success(string $circuitName): void;

    public function failure(string $circuitName): void;

    public function getDelay(string $circuitName): int;
}
