<?php

declare(strict_types = 1);

namespace Kairoi\Test\Domain\Rule\Set\Driver\Runner;

use Kairoi\Domain\Rule\Set\Driver\Runner\Amqp as AmqpDriver;
use Kairoi\Domain\Rule\Set\Runner\Amqp;
use PHPUnit\Framework\TestCase;

final class AmqpTest extends TestCase
{
    /**
     * @dataProvider provideTestGetArguments
     */
    public function testGetArguments(Amqp $runner, array $expected)
    {
        $driver = new AmqpDriver();
        $arguments = $driver->getArguments($runner);

        $this->assertEquals($expected, $arguments);
    }

    public function provideTestGetArguments()
    {
        return [
            [new Amqp('dsn', 'exchange', 'routing_key'), ['amqp', 'dsn', 'exchange', 'routing_key']],
            [new Amqp('amqp://guest:guest@localhost:5672/', 'app.exchange', 'app.kairoi'), ['amqp', 'amqp://guest:guest@localhost:5672/', 'app.exchange', 'app.kairoi']],
        ];
    }
}
