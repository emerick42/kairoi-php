<?php

declare(strict_types = 1);

namespace Kairoi\Domain\Client\Encoding;

/**
 * A request to be encoded as a KCP request.
 */
class Request
{
    /**
     * The identifier of this request, unique accross all active requests for a
     * client.
     *
     * @var string
     */
    private $identifier;

    /**
     * The arguments to be sent to the Kairoi server.
     *
     * @var string[]
     */
    private $arguments;

    /**
     * __construct.
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
     * Get the identifier of this request.
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
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
