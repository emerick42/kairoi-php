<?php

declare(strict_types = 1);

namespace Kairoi\Domain\Client\Decoding;

/**
 * A result of a decoding operation, containing a protocol response on success,
 * or the error description on failure.
 */
class Result
{
    const SUCCESS = 0;
    const FAILURE = 1;
    const INCOMPLETE = 2;

    /**
     * The status of this result (one of SUCCESS, FAILURE or INCOMPLETE).
     *
     * @var int
     */
    private $status;

    /**
     * The protocol response, if this result is a success.
     *
     * @var Response|null
     */
    private $response;

    /**
     * Create a new decoding result. If the status is SUCCESS, there must be
     * a non-null response provided. In other cases, the response must be null.
     *
     * @param int $status
     * @param Response|null $response
     */
    public function __construct(int $status, ?Response $response = null)
    {
        $this->status = $status;
        $this->response = $response;
    }

    /**
     * Get the protocol response. Always return a protocol response if this
     * result is a success, and always return null if it is a failure.
     *
     * @return Response|null
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }

    /**
     * Check if the decoding operation was a failure.
     *
     * @return bool
     */
    public function isFailure(): bool
    {
        return $this->status === self::FAILURE;
    }

    /**
     * Check if the decoding operation was incomplete.
     */
    public function isIncomplete(): bool
    {
        return $this->status === self::INCOMPLETE;
    }
}
