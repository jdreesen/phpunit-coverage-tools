<?php

namespace RobinIngelbrecht\PHPUnitCoverageTools\MinCoverage;

enum ResultStatus: string
{
    case SUCCESS = 'success';
    case WARNING = 'warning';
    case FAILED = 'failed';

    public function getWeight(): int
    {
        return match ($this) {
            self::SUCCESS => 1,
            self::WARNING => 2,
            self::FAILED => 3,
        };
    }

    public function getMessage(): string
    {
        return match ($this) {
            self::SUCCESS => 'All minimum code coverage rules passed, give yourself a pat on the back!',
            self::WARNING => 'There was at least one pattern that did not match any covered classes. Please consider removing them.',
            self::FAILED => 'Not all minimum code coverage rules passed, please try again... :)',
        };
    }
}
