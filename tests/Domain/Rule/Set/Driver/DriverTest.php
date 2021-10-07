<?php

declare(strict_types = 1);

namespace Kairoi\Test\Domain\Rule\Set\Driver;

use Kairoi\Domain\Client\Decoding\Response;
use Kairoi\Domain\Client\Result as ClientResult;
use Kairoi\Domain\Protocol\Request;
use Kairoi\Domain\Rule\Set\Driver\Driver;
use Kairoi\Domain\Rule\Set\Driver\Runner\DriverInterface;
use Kairoi\Domain\Rule\Set\Result;
use Kairoi\Domain\Rule\Set\Rule;
use Kairoi\Domain\Rule\Set\Runner\Amqp;
use PHPUnit\Framework\TestCase;

final class DriverTest extends TestCase
{
    /**
     * @dataProvider provideTestGetRequest
     */
    public function testGetRequest(Rule $rule, Request $expected)
    {
        $fakeRunnerDriver = $this->createMock(DriverInterface::class);
        $fakeRunnerDriver
            ->expects($this->once())
            ->method('getArguments')
            ->willReturn(['fake_runner', 'property'])
        ;

        $driver = new Driver([$fakeRunnerDriver]);
        $request = $driver->getRequest($rule);

        $this->assertEquals($expected, $request);
    }

    public function provideTestGetRequest()
    {
        $rule1 = new Rule('identifier', 'pattern', 'fake_runner');
        $request1 = new Request(['RULE', 'SET', 'identifier', 'pattern', 'fake_runner', 'property']);

        $runner2 = new Amqp('amqp://guest:guest@localhost:5672/', 'app.exchange', 'app.kairoi');
        $rule2 = new Rule('app.rule.😩', 'app.job.😩.', 'fake_runner');
        $request2 = new Request(['RULE', 'SET', 'app.rule.😩', 'app.job.😩.', 'fake_runner', 'property']);

        return [
            [$rule1, $request1],
            [$rule2, $request2],
        ];
    }

    /**
     * @dataProvider provideTestGetResult
     */
    public function testGetResult(ClientResult $clientResult, Result $expected)
    {
        $driver = new Driver([]);
        $result = $driver->getResult($clientResult);

        $this->assertEquals($result, $expected);
    }

    public function provideTestGetResult()
    {
        $clientResult1 = new ClientResult(new Response('0', ['OK']));
        $result1 = new Result(Result::SUCCESS, $clientResult1);
        $clientResult2 = new ClientResult(new Response('1', ['NOK', '0']));
        $result2 = new Result(Result::FAILURE, $clientResult2);
        $clientResult3 = new ClientResult(null);
        $result3 = new Result(Result::FAILURE, $clientResult3);

        return [
            [$clientResult1, $result1],
            [$clientResult2, $result2],
            [$clientResult3, $result3],
        ];
    }
}
