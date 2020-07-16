<?php

declare(strict_types = 1);

namespace Kairoi\Test\Domain\Job\Set;

use Kairoi\Domain\Client\ClientInterface;
use Kairoi\Domain\Client\Result as ClientResult;
use Kairoi\Domain\Job\Set\Driver\DriverInterface;
use Kairoi\Domain\Job\Set\Job;
use Kairoi\Domain\Job\Set\Result;
use Kairoi\Domain\Job\Set\Writer;
use Kairoi\Domain\Protocol\Request;
use Kairoi\Domain\Protocol\Response;
use PHPUnit\Framework\TestCase;

class WriterTest extends TestCase
{
    /**
     * @dataProvider provideTestWrite
     */
    public function testWrite(Job $job, Request $request, ClientResult $clientResult, Result $expected)
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
            ->with($this->equalTo($job))
            ->willReturn($request)
        ;
        $driver
            ->expects($this->once())
            ->method('getResult')
            ->with($this->equalTo($clientResult))
            ->willReturn($expected)
        ;
        $writer = new Writer($client, $driver);

        $result = $writer->write($job);
        $this->assertEquals($expected, $result);
    }

    public function provideTestWrite()
    {
        $rule = new Job('identifier', new \DateTime('2020-07-16 13:16:00', new \DateTimeZone('UTC')));
        $request = new Request(['SET', 'identifier', '2020-07-16 13:16:00']);
        $clientResult = new ClientResult(new Response(['OK']));
        $result = new Result(Result::SUCCESS, $clientResult);

        return [
            [$rule, $request, $clientResult, $result],
        ];
    }
}
