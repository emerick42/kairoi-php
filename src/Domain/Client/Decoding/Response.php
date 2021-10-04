<?php

declare(strict_types = 1);

namespace Kairoi\Domain\Client\Decoding;

/**
 * A response decoded from buffers of a communication under KCP.
 */
class Response
{
    /**
     * The identifier of this response, unique accross all active requests for a
     * client.
     *
     * @var string
     */
    private $identifier;

    /**
     * The list of arguments received from the Kairoi server.
     *
     * @var string[]
     */
    private $arguments;

    /**
     * Create a new response from the given identifier and arguments.
     *
     * @param string $identifier
     * @param string[] $arguments
     */
    public function __construct(string $identifier, array $arguments)
    {
        $this->identifier = $identifier;
        $this->arguments = $arguments;
    }

    /**
     * Get the identifier of this response.
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
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
