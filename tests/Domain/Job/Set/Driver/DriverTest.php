<?php

declare(strict_types = 1);

namespace Kairoi\Test\Domain\Job\Set\Driver;

use Kairoi\Domain\Client\Result as ClientResult;
use Kairoi\Domain\Job\Set\Driver\Driver;
use Kairoi\Domain\Job\Set\Job;
use Kairoi\Domain\Job\Set\Result;
use Kairoi\Domain\Protocol\Request;
use Kairoi\Domain\Protocol\Response;
use PHPUnit\Framework\TestCase;

final class DriverTest extends TestCase
{
    /**
     * @dataProvider provideTestGetRequest
     */
    public function testGetRequest(Job $job, Request $expected)
    {
        $driver = new Driver();
        $request = $driver->getRequest($job);

        $this->assertEquals($expected, $request);
    }

    public function provideTestGetRequest()
    {
        $job1 = new Job('identifier', new \DateTime('2020-07-16 13:38:00', new \DateTimeZone('UTC')));
        $request1 = new Request(['SET', 'identifier', '2020-07-16 13:38:00']);

        $job2 = new Job('app.job.😩', new \DateTimeImmutable('2020-07-16 22:48:00', new \DateTimeZone('Asia/Tokyo')));
        $request2 = new Request(['SET', 'app.job.😩', '2020-07-16 13:48:00']);

        return [
            [$job1, $request1],
            [$job2, $request2],
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
        $clientResult1 = new ClientResult(new Response(['OK']));
        $result1 = new Result(Result::SUCCESS, $clientResult1);
        $clientResult2 = new ClientResult(new Response(['NOK', '0']));
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
