<?php

declare(strict_types = 1);

namespace Kairoi\Domain\Job\Set;

/**
 * A job configuration to be set on the Kairoi server.
 *
 * Each job configuration identifies the job being modified using its unique
 * identifier. The pattern is a starting pattern: all jobs starting by the given
 * string will match this rule (for example, the pattern "app." matches the job
 * "app.job.0"). Finally the runner is a runner configuration to use when a job
 * is paired with this rule for execution.
 */
class Job
{
    /**
     * The unique identifier of the job to be set.
     *
     * @var string
     */
    private $identifier;

    /**
     * The datetime at which this job must be executed.
     *
     * @var \DateTimeInterface
     */
    private $datetime;

    /**
     * Create a new job configuration, setting the job with the given unique
     * identifier to be executed after the given datetime.
     *
     * @param string $identifier
     * @param \DateTimeInterface $datetime
     */
    public function __construct(
        string $identifier,
        \DateTimeInterface $datetime
    ) {
        $this->identifier = $identifier;
        $this->datetime = $datetime;
    }

    /**
     * Get the unique identifier of the job to be set.
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Get the datetime at which this job must be executed.
     *
     * @return \DateTimeInterface
     */
    public function getDatetime(): \DateTimeInterface
    {
        return $this->datetime;
    }
}
