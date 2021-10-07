<?php

declare(strict_types = 1);

namespace Kairoi\Test\Domain\Client\Encoding;

use Kairoi\Domain\Client\Encoding\Encoder;
use Kairoi\Domain\Client\Encoding\Request;
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
            [new Request('0', []), "0\n"],
            [new Request('test', ['solo']), "test solo\n"],
            [new Request('AB-C', ['multiple', 'very.simple', 'arguments']), "AB-C multiple very.simple arguments\n"],
            [new Request('$*()A', ['$im%ple_-but', '/les\\s#simp!1e']), "\$*()A \$im%ple_-but /les\\s#simp!1e\n"],
            [new Request('spaced identifier', ['spaced argument']), "\"spaced identifier\" \"spaced argument\"\n"],
            [new Request("universal\" ide\ntifie\\r", ["universal\" a\\rgume\nt"]), "\"universal\\\" ide\ntifie\\\\r\" \"universal\\\" a\\\\rgume\nt\"\n"],
        ];
    }
}
