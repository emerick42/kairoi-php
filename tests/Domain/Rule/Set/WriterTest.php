<?php

declare(strict_types = 1);

namespace Kairoi\Test\Domain\Rule\Set;

use Kairoi\Domain\Client\ClientInterface;
use Kairoi\Domain\Client\Result as ClientResult;
use Kairoi\Domain\Protocol\Request;
use Kairoi\Domain\Protocol\Response;
use Kairoi\Domain\Rule\Set\Driver\DriverInterface;
use Kairoi\Domain\Rule\Set\Result;
use Kairoi\Domain\Rule\Set\Rule;
use Kairoi\Domain\Rule\Set\Writer;
use PHPUnit\Framework\TestCase;

class WriterTest extends TestCase
{
    /**
     * @dataProvider provideTestWrite
     */
    public function testWrite(Rule $rule, Request $request, ClientResult $clientResult, Result $expected)
    {
        $client = $this->createMock(ClientInterface::class);
        $client
            ->expects($this->once())
            ->method('send')
            ->with($this->equalTo($request))
            ->willReturn($clientResult)
        ;
        $driver = $this->createMock(DriverInterface::class);
        $driver
            ->expects($this->once())
            ->method('getRequest')
            ->with($this->equalTo($rule))
            ->willReturn($request)
        ;
        $driver
            ->expects($this->once())
            ->method('getResult')
            ->with($this->equalTo($clientResult))
            ->willReturn($expected)
        ;
        $writer = new Writer($client, $driver);

        $result = $writer->write($rule);
        $this->assertEquals($expected, $result);
    }

    public function provideTestWrite()
    {
        $rule = new Rule('identifier', 'pattern', 'runner');
        $request = new Request(['RULE', 'SET', 'identifier', 'pattern', 'runner']);
        $clientResult = new ClientResult(new Response(['OK']));
        $result = new Result(Result::SUCCESS, $clientResult);

        return [
            [$rule, $request, $clientResult, $result],
        ];
    }
}
