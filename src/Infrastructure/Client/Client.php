<?php

declare(strict_types = 1);

namespace Kairoi\Infrastructure\Client;

use Kairoi\Domain\Client\ClientInterface;
use Kairoi\Domain\Client\Decoding\DecoderInterface;
use Kairoi\Domain\Client\Encoding\EncoderInterface;
use Kairoi\Domain\Client\Result;
use Kairoi\Domain\Protocol\Request;
use Kairoi\Domain\Protocol\Response;

/**
 * Manage connections with Kairoi servers using PHP stream sockets.
 */
class Client implements ClientInterface
{
    /**
     * The url of the Kairoi server to connect to.
     *
     * @var string
     */
    private $url;

    /**
     * The encoder used to encode requests into raw messages to send through the
     * network.
     *
     * @var EncoderInterface
     */
    private $encoder;

    /**
     * The decoder used to decode raw messages into protocol responses.
     *
     * @var DecoderInterface
     */
    private $decoder;

    /**
     * The underlying socket with the server.
     *
     * @var resource|null
     */
    private $socket;

    /**
     * Create a new client with the Kairoi server at the given URL.
     *
     * The URL should be in the form of 'tcp://domain:port' (see the
     * stream_socket_client function in PHP to check for valid URLs). The
     * encoder is used to encode protocol requests into raw messages to send
     * through the network. The decoder is used to decode raw messages into
     * protocol responses.
     *
     * @param string $url
     * @param EncoderInterface $encoder
     * @param DecoderInterface $decoder
     */
    public function __construct(
        string $url,
        EncoderInterface $encoder,
        DecoderInterface $decoder
    ) {
        $this->url = $url;
        $this->encoder = $encoder;
        $this->decoder = $decoder;
        $this->socket = null;
    }

    /**
     * Shutdown this client, closing the underlying connection with the Kairoi
     * server.
     */
    public function shutdown()
    {
        if ($this->socket !== null) {
            stream_socket_shutdown($this->socket, STREAM_SHUT_RDWR);
            fclose($this->socket);
            $this->socket = null;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function send(Request $request): Result
    {
        // Initialize the connection if needed.
        if ($this->socket === null) {
            $socket = stream_socket_client($this->url, $errno, $errstr, 30);
            if (!$socket) {
                // @TODO: Construct a more descriptive error.
                return new Result(null);
            }

            $this->socket = $socket;
        }

        $message = $this->encoder->encode($request);
        // Make sure to completely send the message.
        $left = $message;
        while (true) {
            $result = fwrite($this->socket, $left);
            if ($result === false) {
                $this->shutdown();

                // @TODO: Construct a more descriptive error.
                return new Result(null);
            }
            if ($result === strlen($left)) {
                break;
            }
            if ($result <= strlen($left)) {
                $left = substr($left, $result);
            }
        }

        $buffer = '';
        while (true) {
            // Check if the socket was closed.
            if (feof($this->socket)) {
                $this->shutdown();

                // @TODO: Construct a more descriptive error.
                return new Result(null);
            }

            $data = fread($this->socket, 1024);
            if ($data === false) {
                $this->shutdown();

                // @TODO: Construct a more descriptive error.
                return new Result(null);
            }

            $buffer .= $data;
            $result = $this->decoder->decode($buffer);

            if ($result->isIncomplete()) {
                continue;
            }
            if ($result->isFailure()) {
                $this->shutdown();

                // @TODO: Construct a more descriptive error.
                return new Result(null);
            }

            return new Result($result->getResponse());
        }
    }
}
