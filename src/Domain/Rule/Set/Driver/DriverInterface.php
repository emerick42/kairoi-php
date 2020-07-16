<?php

declare(strict_types = 1);

namespace Kairoi\Domain\Rule\Set\Driver;

use Kairoi\Domain\Client\Result as ClientResult;
use Kairoi\Domain\Protocol\Request;
use Kairoi\Domain\Rule\Set\Result;
use Kairoi\Domain\Rule\Set\Rule;

/**
 * Construct client requests from rules and results from client results.
 */
interface DriverInterface
{
    /**
     * Construct a client request from the given rule.
     *
     * @param Rule $rule
     *
     * @return Request
     */
    public function getRequest(Rule $rule): Request;

    /**
     * Construct a result from the given client result.
     *
     * @param ClientResult $result
     *
     * @return Result
     */
    public function getResult(ClientResult $result): Result;
}
