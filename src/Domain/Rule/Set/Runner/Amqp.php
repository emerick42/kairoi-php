<?php

declare(strict_types = 1);

namespace Kairoi\Domain\Rule\Set\Runner;

/**
 * An AMQP runner configuration, publishing an AMQP message when a job is paired
 * with it for execution.
 *
 * It is configured using 3 properties:
 * - the data source name, a string in the form of
 * "amqp://user:password@domain:port/vhost", used when the runner is executed as
 * the AMQP server to publish to,
 * - the exchange identifier, a string representing a valid exchange name for an
 * AMQP server, used when the runner is executed as the exchange to publish on,
 * - and the routing key, a string representing a valid routing key for an AMQP
 * server, used when the runner is executed to publish the message.
 */
class Amqp
{
    /**
     * The data source name of the AMQP server to publish to.
     *
     * @var string
     */
    private $dsn;

    /**
     * The identifier of the exchange to publish on.
     *
     * @var string
     */
    private $exchange;

    /**
     * The routing key used to publish the message.
     *
     * @var string
     */
    private $routingKey;

    /**
     * __construct.
     *
     * @param string $dsn
     * @param string $exchange
     * @param string $routingKey
     */
    public function __construct(
        string $dsn,
        string $exchange,
        string $routingKey
    ) {
        $this->dsn = $dsn;
        $this->exchange = $exchange;
        $this->routingKey = $routingKey;
    }

    /**
     * Get the data source name of the AMQP server to publish on.
     *
     * @return string
     */
    public function getDsn(): string
    {
        return $this->dsn;
    }

    /**
     * Get the identifier of the exchange to publish on.
     *
     * @return string
     */
    public function getExchange(): string
    {
        return $this->exchange;
    }

    /**
     * Get the routing key used to publish the message.
     *
     * @return string
     */
    public function getRoutingKey(): string
    {
        return $this->routingKey;
    }
}
