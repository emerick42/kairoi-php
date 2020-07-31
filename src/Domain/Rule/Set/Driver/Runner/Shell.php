<?php

declare(strict_types = 1);

namespace Kairoi\Domain\Rule\Set\Driver\Runner;

use Kairoi\Domain\Rule\Set\Runner\Shell as ShellRunner;

/**
 * Convert Shell runner configurations to client request arguments.
 */
class Shell implements DriverInterface
{
    /**
     * {@inheritDoc}
     */
    public function getArguments($runner): ?array
    {
        if ($runner instanceof ShellRunner) {
            return [
                'shell',
                $runner->getScript(),
            ];
        }

        return null;
    }
}
