<?php

declare(strict_types = 1);

namespace Kairoi\Test\Infrastructure\Client\Decoding;

use Kairoi\Domain\Client\Decoding\Result;
use Kairoi\Domain\Protocol\Response;
use Kairoi\Infrastructure\Client\Decoding\ParsicaDecoder;
use PHPUnit\Framework\TestCase;

class ParsicaDecoderTest extends TestCase
{
    /**
     * @dataProvider provideTestDecode
     */
    public function testDecode(string $input, Result $expected)
    {
        $decoder = new ParsicaDecoder();

        $result = $decoder->decode($input);
        $this->assertEquals($expected, $result);
    }

    public function provideTestDecode()
    {
        return [
            ['', new Result(Result::INCOMPLETE)],
            ['hello', new Result(Result::INCOMPLETE)],
            ["\n", new Result(Result::FAILURE)],
            ["hello\n", new Result(Result::SUCCESS, new Response(['hello']))],
            ["hello\ntoto", new Result(Result::FAILURE)],
            ["hello world\n", new Result(Result::SUCCESS, new Response(['hello', 'world']))],
            ["       he\ll-.o   w#$%orld    \n", new Result(Result::SUCCESS, new Response(['he\ll-.o', 'w#$%orld']))],
            ["\"hello\"\n", new Result(Result::SUCCESS, new Response(['hello']))],
            ["\"hello\" \"world\"\n", new Result(Result::SUCCESS, new Response(['hello', 'world']))],
            ["\"he\\\"l\\\\l\n o\"\n", new Result(Result::SUCCESS, new Response(["he\"l\\l\n o"]))],
        ];
    }
}
