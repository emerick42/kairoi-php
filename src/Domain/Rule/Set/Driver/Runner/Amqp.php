<?php

declare(strict_types = 1);

namespace Kairoi\Domain\Rule\Set\Driver\Runner;

use Kairoi\Domain\Rule\Set\Runner\Amqp as AmqpRunner;

/**
 * Convert AMQP runner configurations to client request arguments.
 */
class Amqp implements DriverInterface
{
    /**
     * {@inheritDoc}
     */
    public function getArguments($runner): ?array
    {
        if ($runner instanceof AmqpRunner) {
            return [
                'amqp',
                $runner->getDsn(),
                $runner->getExchange(),
                $runner->getRoutingKey(),
            ];
        }

        return null;
    }
}
