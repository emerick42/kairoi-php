# Kairoi-PHP

A PHP client for [Kairoi](https://github.com/emerick42/kairoi), a Dynamic, Accurate and Scalable Time-based Job Scheduler.

## Quick Words

Before trying to use this library, you should be familiar with Kairoi's core concepts. If it's not yet the case, you can start by reading the [Kairoi Official documentation](https://github.com/emerick42/kairoi/blob/master/docs/index.md).

About this library, its main goal is to provide an **easy-to-use**, **object-oriented**, and **low-level** programmable interface to communicate with Kairoi servers in PHP. It abstracts managing socket connections with servers, encoding and decoding messages using the [Kairoi Client Protocol](https://github.com/emerick42/kairoi/blob/master/docs/client-protocol.md), and handling [Kairoi Instructions](https://github.com/emerick42/kairoi/blob/master/docs/instructions.md). It should be noted that this library is designed to be used with Dependency Injection Containers, and the associated "Actor/Message" pattern as a specialization of Object-Oriented Programming. While this can definitely be considered as a constraint, it allows Kairoi-PHP to be highly extensible.

The usual workflow to work with Kairoi and this library is the following:
1. _Rules and runners are configured for an application, defining how jobs will be processed by Kairoi._ You can check the [Kairoi Runners documentation](https://github.com/emerick42/kairoi/blob/master/docs/runners.md) for the list of existing runners. Runners should be written to the Kairoi server only once during the deployment of the application, using the programmable interface provided by this library.
2. _Executables (in the application scope) responsible for handling job executions are implemented._ For example, in the case of a `Shell` runner, a CLI can be registered in your favorite framework, taking a job identifier as its main parameter, and executing the domain code associated with the received jobs. This part is outside of the scope of this library.
3. _Jobs are created on the fly by the application during its entire lifetime._ When needed during domain processes, the application can schedule jobs through this library. Kairoi will then automatically trigger the job execution at the proper time, launching the domain code associated with this job, following the Rules and Runners configured at the first step.

## Usage

### Configuring a Client

The first thing you'll need to start communicating with a Kairoi server is a `Kairoi\Domain\Client\ClientInterface`. The library provides `Kairoi\Infrastructure\Client\Client`, as a default implementation of this interface. It takes as a parameter a `string` being the server URL (as accepted by the PHP method [stream_socket_client](https://www.php.net/manual/fr/function.stream-socket-client)), a `Kairoi\Domain\Client\Encoding\EncoderInterface` and a `Kairoi\Domain\Client\Decoding\DecoderInterface`, responsible for respectively encoding `Kairoi\Domain\Protocol\Request`s to a streamable message to send to the server, and decoding streamed messages received from the server to `Kairoi\Domain\Protocol\Response`. Once again, this library provides a `Kairoi\Domain\Client\Encoding\Encoder` to pass as the first parameter, and a `Kairoi\Infrastructure\Client\Decoding\ParsicaDecoder` for the second.

To summarize, you can create a client, configured to communicate with a Kairoi server listening on `127.0.0.1:5678`, with the following code:

```
<?php

use Kairoi\Domain\Client\Encoding\Encoder;
use Kairoi\Infrastructure\Client\Client;
use Kairoi\Infrastructure\Client\Decoding\ParsicaDecoder;

$client = new Client('tcp://localhost:5678', new Encoder(), new ParsicaDecoder());
```

### Setting a Job

Once the client is configured, it can be used to execute instructions. We're going to talk about the [Job Set instruction](https://github.com/emerick42/kairoi/blob/master/docs/instructions.md#job-set) first. Its goal is to set jobs in Kairoi, so they can be triggered at some point. The job will be triggered as soon as its execution date is past. This library provides a `Kairoi\Domain\Job\Set\Writer` to achieve this task. It is constructed with a `Kairoi\Domain\Client\ClientInterface` (the client we configured previously), and a `Kairoi\Domain\Job\Set\Driver\DriverInterface` responsible for converting jobs into standard `Kairoi\Domain\Protocol\Request`s. The library provides the service `Kairoi\Domain\Job\Set\Driver\Driver` as a default implementation for this driver.

Then, we will use the `write` method to set a single job to the Kairoi server. It takes a `Kairoi\Domain\Job\Set\Job` as a parameter, and returns a `Kairoi\Domain\Job\Set\Result`, both being simple Message objects. It should be noted that the returned result will represent the success (or failure) of the "SET" instruction: if the job is written to the Kairoi server, it's a success, but if an error occurs during the communication with the Kairoi server, it's a failure. The Job execution status is completely unrelated.

Here is an example to set the job `app.domain.job.1` to be executed in the future:

```
<?php

use Kairoi\Domain\Job\Set\Driver\Driver;
use Kairoi\Domain\Job\Set\Job;
use Kairoi\Domain\Job\Set\Writer;

$driver = new Driver();
$writer = new Writer($client, $driver);

$job = new Job(
    'app.domain.job.1',
    new \DateTime('+5 minutes')
);
$result = $writer->write($job);
if ($result->isFailure()) {
    printf("Instruction failed to be executed.\n");
}
```

### Setting a Rule

For this job to be executed properly, it needs to match a Kairoi rule that can be set using the [Rule Set instruction](https://github.com/emerick42/kairoi/blob/master/docs/instructions.md#rule-set). Kairoi-PHP provides a `Kairoi\Domain\Rule\Set\Writer`, that works like the Job Writer: it is constructed with a `Kairoi\Domain\Client\ClientInterface` and a `Kairoi\Domain\Rule\Set\Driver\DriverInterface` for converting to standard requests. A default implementation `Kairoi\Domain\Rule\Set\Driver\Driver` is also provided.

The `Kairoi\Domain\Rule\Set\Rule` is the Message object used to configure the rule and its associated runner. It takes a `string` being the unique identifier of this rule as its first parameter, a `string` being the match pattern as a second parameter, and a Runner as a third parameter. In Kairoi, the match pattern is a simple "starts by" comparison: `app.domain.` will match every job starting by this string, such as `app.domain.job.1` or `app.domain.1`, but not `app.domainjob`. The Runner can be anything supported by driver in use.

The default Rule Set driver is a bit more complicated than the Job Set driver. It takes as parameter a collection of `Kairoi\Domain\Rule\Set\Driver\Runner\DriverInterface`, where each element is responsible for handling a type of runner.

An `Shell` runner can be configured as the following:

```
<?php

use Kairoi\Domain\Rule\Set\Driver\Driver;
use Kairoi\Domain\Rule\Set\Driver\Runner\Shell as ShellDriver;
use Kairoi\Domain\Rule\Set\Rule;
use Kairoi\Domain\Rule\Set\Runner\Shell as ShellRunner;
use Kairoi\Domain\Rule\Set\Writer;

$driver = new Driver([new ShellDriver()]);
$writer = new Writer($client, $driver);

$runner = new ShellRunner('/usr/src/app/domain_job.sh');
$rule = new Rule(
    'app.rule.domain.job',
    'app.domain.job.',
    $runner
);
$result = $writer->write($rule);
if ($result->isFailure()) {
    printf("Instruction failed to be executed.\n");
}
```
