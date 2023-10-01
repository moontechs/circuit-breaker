<?php

namespace Moontechs\CircuitBreaker;

class CircuitData
{
    public int $failuresCount = 0;

    public ?int $startedAt = null;
}
