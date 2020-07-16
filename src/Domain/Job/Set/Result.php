<?php

declare(strict_types = 1);

namespace Kairoi\Domain\Job\Set;

use Kairoi\Domain\Client\Result as ClientResult;

/**
 * A result of a JOB SET query write.
 */
class Result
{
    const SUCCESS = 0;
    const FAILURE = 1;

    /**
     * The result of this JOB SET query (one of SUCCESS or FAILURE).
     *
     * @var int
     */
    private $result;

    /**
     * The client result of the query.
     *
     * @var ClientResult
     */
    private $clientResult;

    /**
     * Create a new JOB SET query result.
     *
     * @param int $result
     * @param ClientResult $clientResult
     */
    public function __construct(int $result, ClientResult $clientResult)
    {
        $this->result = $result;
        $this->ClientResult = $clientResult;
    }

    /**
     * Return if request failed to be executed properly.
     *
     * @return bool
     */
    public function isFailure(): bool
    {
        return $this->result === self::FAILURE;
    }

    /**
     * Get the client result for this query.
     *
     * @return ClientResult
     */
    public function getClientResult(): ClientResult
    {
        return $this->clientResult;
    }
}
