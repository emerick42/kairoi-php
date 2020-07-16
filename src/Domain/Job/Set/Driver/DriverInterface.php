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
interface DriverInterface
{
    /**
     * Construct a client request from the given job.
     *
     * @param Job $job
     *
     * @return Request
     */
    public function getRequest(Job $job): Request;

    /**
     * Construct a result from the given client result.
     *
     * @param ClientResult $result
     *
     * @return Result
     */
    public function getResult(ClientResult $result): Result;
}
