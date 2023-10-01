<?php

use Moontechs\CircuitBreaker\Drivers\InMemoryDriver;
use Moontechs\CircuitBreaker\Strategies\CounterStrategy;
use PHPUnit\Framework\TestCase;

class CounterStrategyTest extends TestCase
{
    public function testIsAvailableInMemoryDriver()
    {
        $counterStrategy = new CounterStrategy(new InMemoryDriver());

        $this->assertTrue($counterStrategy->isAvailable('default'));

        $counterStrategy->failure('default');
        $counterStrategy->failure('new');

        $this->assertTrue($counterStrategy->isAvailable('new'));
        $this->assertTrue($counterStrategy->isAvailable('default'));
        $this->assertTrue($counterStrategy->isAvailable('not-existing'));

        $counterStrategy->failure('default');
        $counterStrategy->failure('default');
        $counterStrategy->failure('default');
        $counterStrategy->failure('default');

        $this->assertFalse($counterStrategy->isAvailable('default'));
        $this->assertTrue($counterStrategy->isAvailable('new'));
        $this->assertTrue($counterStrategy->isAvailable('not-existing'));
    }

    public function testSuccessInMemoryDriver()
    {
        $counterStrategy = new CounterStrategy(new InMemoryDriver());

        $counterStrategy->failure('default');
        $counterStrategy->failure('default');
        $counterStrategy->failure('default');
        $counterStrategy->failure('new');

        $this->assertEquals(1, $counterStrategy->getFailuresCount('new'));
        $this->assertEquals(3, $counterStrategy->getFailuresCount('default'));
        $this->assertEquals(0, $counterStrategy->getFailuresCount('not-existing'));

        $counterStrategy->success('default');

        $this->assertEquals(1, $counterStrategy->getFailuresCount('new'));
        $this->assertEquals(0, $counterStrategy->getFailuresCount('default'));
        $this->assertEquals(0, $counterStrategy->getFailuresCount('not-existing'));
    }
}
