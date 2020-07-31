<?php

declare(strict_types = 1);

namespace Kairoi\Test\Domain\Rule\Set\Driver\Runner;

use Kairoi\Domain\Rule\Set\Driver\Runner\Shell as ShellDriver;
use Kairoi\Domain\Rule\Set\Runner\Shell;
use PHPUnit\Framework\TestCase;

final class ShellTest extends TestCase
{
    /**
     * @dataProvider provideTestGetArguments
     */
    public function testGetArguments(Shell $runner, array $expected)
    {
        $driver = new ShellDriver();
        $arguments = $driver->getArguments($runner);

        $this->assertEquals($expected, $arguments);
    }

    public function provideTestGetArguments()
    {
        return [
            [new Shell('test_script.sh'), ['shell', 'test_script.sh']],
            [new Shell('/var/script.sh'), ['shell', '/var/script.sh']],
        ];
    }
}
