<?php

declare(strict_types = 1);

namespace Kairoi\Domain\Protocol;

/**
 * A response as defined by the Kairoi Client Protocol, containing a list of
 * arguments of type string.
 */
class Response
{
    /**
     * The list of arguments received from the Kairoi server.
     *
     * @var string[]
     */
    private $arguments;

    /**
     * Create a new response from the given arguments.
     *
     * @param string[] $arguments
     */
    public function __construct(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * Get the list of arguments received from the Kairoi server.
     *
     * @return string[]
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
}
