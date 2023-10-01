# Circuit breaker

An in-memory (default driver) circuit breaker implementation with different strategies.
It is very useful if you have a long job that does a lot of HTTP requests in a loop. Check the [Example](#example) section.

No dependencies.

### Installation

`composer require moontechs/circuit-breaker`

### Usage

#### Choose a strategy
* Counter strategy - simple failures increment. When a limit is reached `isAvailable` function returns false.
  Calling the `success` function will reset the counter (will set 0).
  * `limit` - the default value is 5. Use the `setLimit(int)` method to change it.

```php
use Moontechs\CircuitBreaker\CircuitBreaker;
use Moontechs\CircuitBreaker\Drivers\InMemoryDriver;
use Moontechs\CircuitBreaker\Strategies\CounterStrategy;

$circuitBreaker = new CircuitBreaker(
    new CounterStrategy(
        new InMemoryDriver()
    )
);
```

* Time frame strategy - failures increment during a specified time frame. When a limit is reached during the time frame `isAvailable` function returns false.
  Calling the `success` function will reset the counter (will set 0).
  * `limit` - the default value is 5. Use the `setLimit(int)` method to change it.
  * `timeFrame` - the default value is 300 seconds. Use the `setTimeFrame(int)` method to change it.

```php
use Moontechs\CircuitBreaker\CircuitBreaker;
use Moontechs\CircuitBreaker\Drivers\InMemoryDriver;
use Moontechs\CircuitBreaker\Strategies\TimeFrameStrategy;

$circuitBreaker = new CircuitBreaker(
    new TimeFrameStrategy(
        new InMemoryDriver()
    )
);
```

The default driver is `InMemoryDriver`. It does not share the data between processes.

#### Example

An example of a real-world usage.

```php
use Moontechs\CircuitBreaker\CircuitBreaker;
use Moontechs\CircuitBreaker\Drivers\InMemoryDriver;
use Moontechs\CircuitBreaker\Strategies\CounterStrategy;

$circuitBreaker = new CircuitBreaker(
    new CounterStrategy(
        new InMemoryDriver()
    )
);

foreach ($data as $datum) {
    try {
        exampleHttpRequest($datum);
        
        // This is optional. In case if you want to reset the failures counter.
        $circuitBreaker->success('example');
    } catch (\Exception $exception) {
        $circuitBreaker->failure('example');
        
        // The following condition could be used to throw an exception or repeat a request later.
        if (!$circuitBreaker->isAvailable('example')) {
            throw $exception;
        }
        
        // This is optional. In case you want to prevent another service from spamming and give it some time to recover.
        sleep($circuitBreaker->getFailuresCount() * 10);
    }
}
```