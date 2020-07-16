<?php

declare(strict_types = 1);

namespace Kairoi\Domain\Protocol;

/**
 * A request as defined by the Kairoi Client Protocol, containing a list of
 * arguments of type string.
 */
class Request
{
    /**
     * The arguments to be sent to the Kairoi server.
     *
     * @var string[]
     */
    private $arguments;

    /**
     * __construct.
     *
     * @param string[] $arguments
     */
    public function __construct(array $arguments)
    {
        $this->arguments = $arguments;
    }

    /**
     * Get the arguments to be sent to the Kairoi server.
     *
     * @return string[]
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
}
