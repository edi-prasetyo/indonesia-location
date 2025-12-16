<?php

namespace EdiPrasetyo\IndonesiaLocation;

use Illuminate\Support\ServiceProvider;
use EdiPrasetyo\IndonesiaLocation\Console\InstallCommand;

class IndonesiaLocationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Publish migrations
        $this->publishes([
            __DIR__ . '/database/migrations' => database_path('migrations'),
        ], 'indonesia-location-migrations');

        // Register command
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }
}
