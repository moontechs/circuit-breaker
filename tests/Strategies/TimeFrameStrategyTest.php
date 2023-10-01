<?php

use Moontechs\CircuitBreaker\Drivers\InMemoryDriver;
use Moontechs\CircuitBreaker\Strategies\CounterStrategy;
use Moontechs\CircuitBreaker\Strategies\TimeFrameStrategy;
use PHPUnit\Framework\TestCase;

class TimeFrameStrategyTest extends TestCase
{
    public function testIsAvailableInMemoryDriver()
    {
        $timeFrameStrategy = new TimeFrameStrategy(new InMemoryDriver());

        $this->assertTrue($timeFrameStrategy->isAvailable('default'));

        $timeFrameStrategy->failure('default');

        $this->assertTrue($timeFrameStrategy->isAvailable('default'));

        $timeFrameStrategy->failure('default');
        $timeFrameStrategy->failure('default');
        $timeFrameStrategy->failure('default');
        $timeFrameStrategy->failure('default');

        $this->assertFalse($timeFrameStrategy->isAvailable('default'));
    }

    public function testIsAvailableTimeFramePassedInMemoryDriver()
    {
        $inMemoryDriver = new InMemoryDriver();
        $timeFrameStrategy = new TimeFrameStrategy($inMemoryDriver);

        $this->assertTrue($timeFrameStrategy->isAvailable('default'));

        $timeFrameStrategy->failure('default');

        $this->assertTrue($timeFrameStrategy->isAvailable('default'));

        $timeFrameStrategy->failure('default');
        $timeFrameStrategy->failure('default');
        $timeFrameStrategy->failure('default');
        $timeFrameStrategy->failure('default');

        $this->assertFalse($timeFrameStrategy->isAvailable('default'));

        $circuitData = $inMemoryDriver->get('default');
        $circuitData->startedAt = strtotime('-301 seconds');
        $inMemoryDriver->set('default', $circuitData);

        $this->assertTrue($timeFrameStrategy->isAvailable('default'));
    }

    public function testFailureTimeFramePassed()
    {
        $inMemoryDriver = new InMemoryDriver();
        $timeFrameStrategy = new TimeFrameStrategy($inMemoryDriver);
        $timeFrameStrategy->failure('default');
        $timeFrameStrategy->failure('default');

        $this->assertEquals(2, $timeFrameStrategy->getFailuresCount('default'));

        $circuitData = $inMemoryDriver->get('default');
        $circuitData->startedAt = strtotime('-301 seconds');
        $inMemoryDriver->set('default', $circuitData);

        $timeFrameStrategy->failure('default');

        $this->assertEquals(1, $timeFrameStrategy->getFailuresCount('default'));
    }

    public function testSuccessInMemoryDriver()
    {
        $timeFrameStrategy = new TimeFrameStrategy(new InMemoryDriver());

        $timeFrameStrategy->failure('default');
        $timeFrameStrategy->failure('default');
        $timeFrameStrategy->failure('default');

        $this->assertEquals(3, $timeFrameStrategy->getFailuresCount('default'));

        $timeFrameStrategy->success('default');

        $this->assertEquals(0, $timeFrameStrategy->getFailuresCount('default'));
    }
}
