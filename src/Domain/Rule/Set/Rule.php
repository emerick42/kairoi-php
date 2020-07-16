<?php

declare(strict_types = 1);

namespace Kairoi\Domain\Rule\Set;

/**
 * A rule configuration to be set on the Kairoi server.
 *
 * Each rule configuration identifies the rule being modified using its unique
 * identifier. The pattern is a starting pattern: all jobs starting by the given
 * string will match this rule (for example, the pattern "app." matches the job
 * "app.job.0"). Finally the runner is a runner configuration to use when a job
 * is paired with this rule for execution.
 */
class Rule
{
    /**
     * The unique identifier of this rule configuration.
     *
     * @var string
     */
    private $identifier;

    /**
     * The pattern used to match jobs with this rule configuration.
     *
     * @var string
     */
    private $pattern;

    /**
     * The runner configuration used when this rule is paired with a job for
     * execution.
     *
     * @var mixed
     */
    private $runner;

    /**
     * __construct.
     *
     * @param string $identifier
     * @param string $pattern
     * @param mixed $runner
     */
    public function __construct(
        string $identifier,
        string $pattern,
        $runner
    ) {
        $this->identifier = $identifier;
        $this->pattern = $pattern;
        $this->runner = $runner;
    }

    /**
     * Get the unique identifier of this rule configuration.
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Get the pattern used to match jobs with this rule configuration.
     *
     * @return string
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * Get the runner configuration used when this rule is paired with a job for
     * execution.
     *
     * @return mixed
     */
    public function getRunner()
    {
        return $this->runner;
    }
}
