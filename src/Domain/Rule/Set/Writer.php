<?php

declare(strict_types = 1);

namespace Kairoi\Domain\Rule\Set;

use Kairoi\Domain\Client\ClientInterface;
use Kairoi\Domain\Rule\Set\Driver\DriverInterface;

/**
 * Write RULE SET queries to a Kairoi server, through a configured client.
 */
class Writer
{
    /**
     * The client used to send RULE SET queries to the Kairoi server.
     *
     * @var ClientInterface
     */
    private $client;

    /**
     * The driver used to construct client requests from rules and results from
     * client results.
     *
     * @var DriverInterface
     */
    private $driver;

    /**
     * Create a new writer configured with the given client and given driver.
     *
     * @param ClientInterface $client
     * @param DriverInterface[] $driver
     */
    public function __construct(
        ClientInterface $client,
        DriverInterface $driver
    ) {
        $this->client = $client;
        $this->driver = $driver;
    }

    /**
     * Write a RULE SET query with the given rule configuration using the
     * configured client.
     *
     * @param Rule $rule
     *
     * @return Result
     */
    public function write(Rule $rule): Result
    {
        // Construct the request and use the client to send it, then retrieve
        // the response.
        $request = $this->driver->getRequest($rule);
        $clientResult = $this->client->send($request);
        $result = $this->driver->getResult($clientResult);

        return $result;
    }
}
