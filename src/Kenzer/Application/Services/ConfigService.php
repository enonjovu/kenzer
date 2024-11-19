<?php

declare(strict_types=1);

namespace Kenzer\Application\Services;

use Kenzer\Application\Application;
use Kenzer\Application\ConfigLoader;
use Kenzer\Interface\Application\ApplicationServiceInterface;

class ConfigService implements ApplicationServiceInterface
{
    public function __construct(private Application $application) {}

    public function boot(): void
    {
        $this->application->singleton('config.view.directives', function () {
            $config = require_once __DIR__.'/../config/view.php';

            return $config['directives'];
        });

        $this->application->singleton('config.app', fn () => new ConfigLoader(__DIR__.'/../config/app.php'));
    }
}
