<?php

namespace Mindwingx\ServiceCallAdapter;

use Illuminate\Support\ServiceProvider;
use Mindwingx\ServiceCallAdapter\Commands\ServiceGeneratorCommand;

class ServiceCallServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind('service-call', function () {
            return new ServiceCall();
        });
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ServiceGeneratorCommand::class,
            ]);
        }
    }
}
