<?php

declare(strict_types = 1);

namespace Kairoi\Domain\Client;

use Kairoi\Domain\Protocol\Request;

/**
 * Manage connections with Kairoi servers.
 */
interface ClientInterface
{
    /**
     * Send the given requests to the Kairoi server, returning their responses.
     * The resulting array is indexed like the request array.
     *
     * @param Request[] $requests
     *
     * @return Result[]
     */
    public function send(array $requests): array;
}
