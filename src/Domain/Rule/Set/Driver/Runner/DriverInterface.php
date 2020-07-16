<?php

declare(strict_types = 1);

namespace Kairoi\Domain\Rule\Set\Driver\Runner;

/**
 * Convert specific runner configurations to client request arguments.
 */
interface DriverInterface
{
    /**
     * Construct the client request arguments for the given runner. Return null
     * if this driver does not support the given runner type.
     *
     * @param mixed $runner
     *
     * @return string[]|null
     */
    public function getArguments($runner): ?array;
}
