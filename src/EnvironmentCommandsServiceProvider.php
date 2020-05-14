<?php

namespace TamerDev\EnvironmentCommands;

use Illuminate\Support\ServiceProvider;
use TamerDev\EnvironmentCommands\EnvironmentSetCommand;
use TamerDev\EnvironmentCommands\EnvironmentReadCommand;

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
        $this->commands([
            'command.env:set','command.env:read'
        ]);
    }
}
