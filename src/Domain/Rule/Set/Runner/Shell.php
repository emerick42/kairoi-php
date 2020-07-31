<?php

declare(strict_types = 1);

namespace Kairoi\Domain\Rule\Set\Runner;

/**
 * A Shell runner configuration, executing a shell script when a job is paired
 * with it for execution.
 *
 * It is configured using a single property:
 * - the path (on the Kairoi server filesystem) of the shell script executed, as
 * a string. It can be absolute ("/var/script.sh") or relative to the Kairoi
 * server's running directory ("./scripts/script.sh").
 */
class Shell
{
    /**
     * The path of the script to be executed.
     *
     * @var string
     */
    private $script;

    /**
     * Create a new shell runner configuration.
     *
     * @param string $script
     */
    public function __construct(string $script)
    {
        $this->script = $script;
    }

    /**
     * Get the path of the script to be executed.
     *
     * @return string
     */
    public function getScript(): string
    {
        return $this->script;
    }
}
