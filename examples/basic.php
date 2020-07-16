<?php

use Kairoi\Domain\Client\Encoding\Encoder;
use Kairoi\Domain\Job\Set\Driver\Driver as JobSetDriver;
use Kairoi\Domain\Job\Set\Job;
use Kairoi\Domain\Job\Set\Writer as JobSetWriter;
use Kairoi\Domain\Rule\Set\Driver\Driver as RuleSetDriver;
use Kairoi\Domain\Rule\Set\Driver\Runner\Amqp as RuleSetAmqpDriver;
use Kairoi\Domain\Rule\Set\Rule;
use Kairoi\Domain\Rule\Set\Runner\Amqp as RuleSetAmqpRunner;
use Kairoi\Domain\Rule\Set\Writer as RuleSetWriter;
use Kairoi\Infrastructure\Client\Client;

// Create a client on the Kairoi server listening on tcp://localhost:5678.
$client = new Client('tcp://localhost:5678', new Encoder());

// Configure an AMQP runner to publish messages to an AMQP server listening on
// "amqp://localhost:5672" using the virtual host "/", on the "app" exchange and
// using the "app_kairoi" routing key.
$runner = new RuleSetAmqpRunner('amqp://guest:guest@localhost:5672/', 'app', 'app_kairoi');
// Configure a rule with the identifier "app.default.rule" matching all job
// identifiers starting by "app." with the configured runner.
$rule = new Rule('app.default.rule', 'app.', $runner);
// Write the configured rule to the Kairoi server.
$ruleSetDriver = new RuleSetDriver([new RuleSetAmqpDriver()]);
$ruleSetWriter = new RuleSetWriter($client, $ruleSetDriver);
$result = $ruleSetWriter->write($rule);
if ($result->isFailure()) {
    // An error occurred.
}

// Configure a job with the identifier "app.domain.job.0", to be executed as
// soon as possible.
$job = new Job('app.domain.job.0', new \DateTime('now'));
// Write the configured job to the Kairoi server.
$jobSetDriver = new JobSetDriver();
$jobSetWriter = new JobSetWriter($client, $jobSetDriver);
$result = $jobSetWriter->write($job);
if ($result->isFailure()) {
    // An error occurred.
}

// Shutdown the connection with the Kairoi server when it's not used anymore.
$client->shutdown();
