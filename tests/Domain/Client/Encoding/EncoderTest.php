<?php

declare(strict_types = 1);

namespace Kairoi\Test\Domain\Client\Encoding;

use Kairoi\Domain\Client\Encoding\Encoder;
use Kairoi\Domain\Protocol\Request;
use PHPUnit\Framework\TestCase;

class EncoderTest extends TestCase
{
    /**
     * @dataProvider provideTestEncode
     */
    public function testEncode(Request $request, string $expected)
    {
        $encoder = new Encoder();

        $result = $encoder->encode($request);
        $this->assertEquals($expected, $result);
    }

    public function provideTestEncode()
    {
        return [
            [new Request([]), "\n"],
            [new Request(['solo']), "solo\n"],
            [new Request(['multiple', 'very.simple', 'arguments']), "multiple very.simple arguments\n"],
            [new Request(['$im%ple_-but', '/les\\s#simp!1e']), "\$im%ple_-but /les\\s#simp!1e\n"],
            [new Request(['spaced argument']), "\"spaced argument\"\n"],
            [new Request(["universal\" a\\rgume\nt"]), "\"universal\\\" a\\\\rgume\nt\"\n"],
        ];
    }
}
