<?php

declare(strict_types = 1);

namespace Kairoi\Domain\Client\Encoding;

/**
 * Encode protocol requests into raw string.
 */
class Encoder implements EncoderInterface
{
    /**
     * {@inheritDoc}
     */
    public function encode(Request $request): string
    {
        $message = $this->encodeArgument($request->getIdentifier());
        foreach ($request->getArguments() as $argument) {
            $encoded = $this->encodeArgument($argument);
            $message .= ' ' . $encoded;
        }
        $message .= "\n";

        return $message;
    }

    /**
     * Encode the given argument into a raw string.
     *
     * @param string $argument
     *
     * @return string
     */
    private function encodeArgument(string $argument): string
    {
        $complex = strpos($argument, ' ') || strpos($argument, '"') || strpos($argument, "\n");
        if ($complex) {
            $result = '"' . str_replace(['\\', '"'], ['\\\\', '\\"'], $argument) . '"';

            return $result;
        }

        return $argument;
    }
}
