<?php

namespace Moontechs\CircuitBreaker\Strategies;

trait CircuitsArrayManagerTrait
{
    private array $circuits = [];

    private function getCircuitByName(string $circuitName): array
    {
        if (isset($this->circuits[$circuitName])) {
            return $this->circuits[$circuitName];
        }

        return [];
    }

    private function setCircuitByName(string $circuitName, array $circuit): void
    {
        $this->circuits[$circuitName] = $circuit;
    }
}
