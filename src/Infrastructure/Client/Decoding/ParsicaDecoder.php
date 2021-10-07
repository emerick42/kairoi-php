<?php

declare(strict_types = 1);

namespace Kairoi\Infrastructure\Client\Decoding;

use Kairoi\Domain\Client\Decoding\DecoderInterface;
use Kairoi\Domain\Client\Decoding\Response;
use Kairoi\Domain\Client\Decoding\Result;
use Verraes\Parsica;

/**
 * Decode raw strings into protocol responses using Parsica.
 */
class ParsicaDecoder implements DecoderInterface
{
    /**
     * {@inheritDoc}
     */
    public function decode(string $input): Result
    {
        $space = Parsica\char(' ');
        $spaces = Parsica\atLeastOne($space);
        $endline = Parsica\char("\n");
        $simpleCharacter = Parsica\noneOf([' ', "\n", '"']);
        $simpleString = Parsica\atLeastOne($simpleCharacter);
        $escapedCharacter = Parsica\either(
            Parsica\keepSecond(
                Parsica\char('\\'),
                Parsica\char('\\'),
            ),
            Parsica\keepSecond(
                Parsica\char('\\'),
                Parsica\char('"'),
            ),
        );
        $nonEscapedCharacter = Parsica\noneOf(['\\', '"']);
        $escapedStringCharacter = Parsica\either(
            $escapedCharacter,
            $nonEscapedCharacter
        );
        $escapedString = Parsica\atLeastOne($escapedStringCharacter);
        $universalString = Parsica\between(
            Parsica\char('"'),
            Parsica\char('"'),
            $escapedString
        );
        $string = Parsica\either(
            $simpleString,
            $universalString
        );
        $argument = Parsica\between(
            Parsica\optional($spaces),
            Parsica\optional($spaces),
            $string,
        );
        $arguments = Parsica\some($argument);
        $content = Parsica\collect($argument, $arguments);
        $response = Parsica\keepFirst(
            $content,
            $endline
        );
        $parser = Parsica\some($response);

        $stream = new Parsica\StringStream($input);
        $result = $parser->run($stream);

        if ($result->isSuccess()) {
            $inputLeft = null;
            if (!$result->remainder()->isEOF()) {
                $inputLeft = (string)$result->remainder();
            }

            $responses = [];
            foreach ($result->output() as [$identifier, $arguments]) {
                $responses[] = new Response($identifier, $arguments);
            }

            return new Result(Result::SUCCESS, $inputLeft, $responses);
        }

        if ($result->got()->isEOF()) {
            return new Result(Result::INCOMPLETE);
        }

        return new Result(Result::FAILURE);
    }
}
