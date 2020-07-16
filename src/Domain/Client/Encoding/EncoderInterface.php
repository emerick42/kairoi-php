<?php

declare(strict_types = 1);

namespace Kairoi\Domain\Client\Encoding;

use Kairoi\Domain\Protocol\Request;

/**
 * Encode protocol requests into raw strings.
 */
interface EncoderInterface
{
    /**
     * Encode the given protocol request into a raw string to be sent over the
     * network.
     *
     * @param Request $request
     *
     * @return string
     */
    public function encode(Request $request): string;
}
