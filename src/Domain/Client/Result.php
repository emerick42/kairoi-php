<?php

declare(strict_types = 1);

namespace Kairoi\Domain\Client;

use Kairoi\Domain\Client\Decoding\Response;

/**
 * A result from a request sending to a Kairoi server.
 */
class Result
{
    /**
     * The response received from the kairoi server.
     *
     * @var Response|null
     */
    private $response;

    /**
     * Create a new result for a query.
     *
     * @param Response|null $response
     */
    public function __construct(?Response $response)
    {
        $this->response = $response;
    }

    /**
     * Get the response received from the Kairoi server, or null if an error
     * occurred during the query.
     *
     * @return Response|null
     */
    public function getResponse(): ?Response
    {
        return $this->response;
    }
}
