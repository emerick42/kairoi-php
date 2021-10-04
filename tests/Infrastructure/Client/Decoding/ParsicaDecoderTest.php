<?php

declare(strict_types = 1);

namespace Kairoi\Test\Infrastructure\Client\Decoding;

use Kairoi\Domain\Client\Decoding\Response;
use Kairoi\Domain\Client\Decoding\Result;
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
            ["A\n", new Result(Result::FAILURE)],
            ["0 hello\n", new Result(Result::SUCCESS, new Response('0', ['hello']))],
            ["TEST hello\ntoto", new Result(Result::FAILURE)],
            ["id hello world\n", new Result(Result::SUCCESS, new Response('id', ['hello', 'world']))],
            ["  1d3nt\$fi-r     he\ll-.o   w#$%orld    \n", new Result(Result::SUCCESS, new Response('1d3nt$fi-r', ['he\ll-.o', 'w#$%orld']))],
            ["\"001\" \"hello\"\n", new Result(Result::SUCCESS, new Response('001', ['hello']))],
            ["\"ident ifier\" \"hello\" \"world\"\n", new Result(Result::SUCCESS, new Response('ident ifier', ['hello', 'world']))],
            ["\"ide\\\"ti\nfier\" \"he\\\"l\\\\l\n o\"\n", new Result(Result::SUCCESS, new Response("ide\"ti\nfier", ["he\"l\\l\n o"]))],
        ];
    }
}
