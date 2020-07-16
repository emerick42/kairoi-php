<?php

declare(strict_types = 1);

namespace Kairoi\Domain\Job\Set\Driver;

use Kairoi\Domain\Client\Result as ClientResult;
use Kairoi\Domain\Job\Set\Job;
use Kairoi\Domain\Job\Set\Result;
use Kairoi\Domain\Protocol\Request;

/**
 * Construct client requests from jobs and results from client results.
 */
class Driver implements DriverInterface
{
    /**
     * {@inheritDoc}
     */
    public function getRequest(Job $job): Request
    {
        $utc = new \DateTime($job->getDatetime()->format('r'));
        $utc->setTimeZone(new \DateTimeZone('UTC'));

        $arguments = [
            'SET',
            $job->getIdentifier(),
            $utc->format('Y-m-d H:i:s'),
        ];

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
