<?php

declare(strict_types = 1);

namespace Kairoi\Domain\Client\Decoding;

/**
 * Decode raw strings into protocol responses.
 */
interface DecoderInterface
{
    /**
     * Decode the given input into a structured result, containing the input
     * left to parse, all parsed protocol responses, or the error description.
     * In case of a INCOMPLETE result, the decoding operation should be retried
     * with more input.
     *
     * @param string $data
     *
     * @return Result
     */
    public function decode(string $input): Result;
}
