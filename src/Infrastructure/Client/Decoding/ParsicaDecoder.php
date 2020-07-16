<?php

declare(strict_types = 1);

namespace Kairoi\Infrastructure\Client\Decoding;

use Kairoi\Domain\Client\Decoding\DecoderInterface;
use Kairoi\Domain\Client\Decoding\Result;
use Kairoi\Domain\Protocol\Response;
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
        $parser = Parsica\keepFirst(
            $arguments,
            $endline
        );

        $stream = new Parsica\StringStream($input);
        $result = $parser->run($stream);

        if ($result->isSuccess()) {
            if ($result->remainder()->isEOF()) {
                return new Result(Result::SUCCESS, new Response($result->output()));
            }

            return new Result(Result::FAILURE);
        }

        if ($result->got()->isEOF()) {
            return new Result(Result::INCOMPLETE);
        }

        return new Result(Result::FAILURE);
    }
}
