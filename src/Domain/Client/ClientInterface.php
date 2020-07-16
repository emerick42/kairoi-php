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
     * Send the given request to the Kairoi server, returning its response.
     *
     * @param Request $request
     *
     * @return Result
     */
    public function send(Request $request): Result;
}
