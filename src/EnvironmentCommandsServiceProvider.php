<?php

namespace TamerDev\EnvironmentCommands;

use Illuminate\Support\ServiceProvider;
use TamerDev\EnvironmentCommands\EnvironmentSetCommand;
use TamerDev\EnvironmentCommands\EnvironmentReadCommand;
use TamerDev\EnvironmentCommands\EnvironmentBackupCommand;
use TamerDev\EnvironmentCommands\EnvironmentRestoreCommand;

class EnvironmentCommandsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // ...
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind('command.env:set', EnvironmentSetCommand::class);
        $this->app->bind('command.env:read', EnvironmentReadCommand::class);
        $this->app->bind('command.env:backup', EnvironmentBackupCommand::class);
        $this->app->bind('command.env:restore', EnvironmentRestoreCommand::class);

        $this->commands([
            'command.env:set','command.env:read','command.env:backup','command.env:restore'
        ]);
    }
}
