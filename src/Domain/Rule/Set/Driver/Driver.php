<?php

declare(strict_types = 1);

namespace Kairoi\Domain\Rule\Set\Driver;

use Kairoi\Domain\Client\Result as ClientResult;
use Kairoi\Domain\Protocol\Request;
use Kairoi\Domain\Rule\Set\Driver\Runner\DriverInterface as RunnerDriverInterface;
use Kairoi\Domain\Rule\Set\Result;
use Kairoi\Domain\Rule\Set\Rule;

/**
 * Construct client requests from rules and results from client results.
 */
class Driver implements DriverInterface
{
    /**
     * The registered drivers used to construct client request arguments for
     * runners.
     *
     * @var RunnerDriverInterface[]
     */
    private $runnerDrivers;

    /**
     * Create a new driver with the given runner drivers.
     *
     * Runner drivers are required to properly construct a RULE SET request to a
     * server. If no driver is able to construct arguments for a rule, the
     * request will be constructed with missing arguments (making it fail when
     * sent).
     *
     * @param RunnerDriverInterface[]
     */
    public function __construct(array $runnerDrivers)
    {
        $this->runnerDrivers = $runnerDrivers;
    }

    /**
     * {@inheritDoc}
     */
    public function getRequest(Rule $rule): Request
    {
        // Construct rule arguments.
        $arguments = [
            'RULE',
            'SET',
            $rule->getIdentifier(),
            $rule->getPattern(),
        ];

        // Construct runner arguments.
        $runnerArguments = [];
        foreach ($this->runnerDrivers as $driver) {
            $result = $driver->getArguments($rule->getRunner());
            if ($result !== null) {
                $runnerArguments = $result;

                break;
            }
        }

        // Append runner arguments to rule arguments.
        $arguments = array_merge($arguments, $runnerArguments);

        $request = new Request($arguments);

        return $request;
    }

    /**
     * {@inheritDoc}
     */
    public function getResult(ClientResult $clientResult): Result
    {
        $response = $clientResult->getResponse();
        if ($response === null) {
            return new Result(Result::FAILURE, $clientResult);
        }

        $arguments = $response->getArguments();
        if (count($arguments) === 1 && $arguments[0] === 'OK') {
            return new Result(Result::SUCCESS, $clientResult);
        }

        return new Result(Result::FAILURE, $clientResult);
    }
}
