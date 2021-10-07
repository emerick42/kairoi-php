<?php

declare(strict_types = 1);

namespace Kairoi\Domain\Job\Set;

use Kairoi\Domain\Client\ClientInterface;
use Kairoi\Domain\Job\Set\Driver\DriverInterface;

/**
 * Write JOB SET queries to a Kairoi server, through a configured client.
 */
class Writer
{
    /**
     * The client used to send JOB SET queries to the Kairoi server.
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
     * Write a JOB SET query with the given job using the configured client.
     *
     * @param Job $job
     *
     * @return Result
     */
    public function write(Job $job): Result
    {
        // Construct the request and use the client to send it, then retrieve
        // the response.
        $request = $this->driver->getRequest($job);
        [$clientResult] = $this->client->send([$request]);
        $result = $this->driver->getResult($clientResult);

        return $result;
    }

    /**
     * Write multiple JOB SET query with the given jobs using the configured
     * client. Each query can fail independently. Return an array of results
     * indexed like the given jobs array.
     *
     * @param Job[] $jobs
     *
     * @return Result[]
     */
    public function writeMany(array $jobs): array
    {
        $requests = [];
        foreach ($jobs as $index => $job) {
            $requests[$index] = $this->driver->getRequest($job);
        }

        $clientResults = $this->client->send($requests);

        $results = [];
        foreach ($clientResults as $index => $clientResult) {
            $results[$index] = $this->driver->getResult($clientResult);
        }

        return $results;
    }
}
