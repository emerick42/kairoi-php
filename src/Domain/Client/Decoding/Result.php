<?php

declare(strict_types = 1);

namespace Kairoi\Domain\Client\Decoding;

/**
 * A result of a decoding operation, containing the input left to parse, some
 * protocol responses on success, or the error description on failure.
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
     * The input left after parsing, or null if there is none.
     *
     * @var string|null
     */
    private $inputLeft;

    /**
     * The protocol responses that got parsed, if this result is a success.
     *
     * @var Response[]|null
     */
    private $responses;

    /**
     * Create a new decoding result. If the status is SUCCESS, there must be
     * a non-null response provided. In other cases, the response must be null.
     *
     * @param int $status
     * @param string|null $inputLeft
     * @param Response[]|null $responses
     */
    public function __construct(int $status, ?string $inputLeft = null, ?array $responses = null)
    {
        $this->status = $status;
        $this->inputLeft = $inputLeft;
        $this->responses = $responses;
    }

    /**
     * Get the protocol responses. Always return an array if this result is a
     * success, and always return null if it is a failure.
     *
     * @return Response[]|null
     */
    public function getResponses(): ?array
    {
        return $this->responses;
    }

    /**
     * Get the input left to parse. Return null if there is none.
     *
     * @return string|null
     */
    public function getInputLeft(): ?string
    {
        return $this->inputLeft;
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
