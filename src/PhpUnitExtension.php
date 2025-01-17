<?php

namespace RobinIngelbrecht\PHPUnitCoverageTools;

use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration as PHPUnitConfiguration;
use RobinIngelbrecht\PHPUnitCoverageTools\Subscriber\Application\ApplicationFinishedSubscriber;

final class PhpUnitExtension implements Extension
{
    public function bootstrap(
        PHPUnitConfiguration $configuration,
        Facade $facade,
        ParameterCollection $parameters): void
    {
        if (!$subscriber = ApplicationFinishedSubscriber::fromConfigurationAndParameters(
            $configuration,
            $parameters,
            $_SERVER['argv'],
        )) {
            return;
        }

        $facade->registerSubscribers(
            $subscriber,
        );
    }
}
