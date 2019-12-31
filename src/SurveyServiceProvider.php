<?php

namespace Sniper7Kills\Survey;

use Illuminate\Support\ServiceProvider;

class SurveyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/survey.php', 'survey'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__.'/../views', 'survey');

        $this->publishes([
            __DIR__.'/../config/survey.php' => config_path('survey.php'),
            __DIR__.'/../views' => resource_path('views/vendor/survey'),
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ],'survey-all');
        $this->publishes([
            __DIR__.'/../config/survey.php' => config_path('survey.php'),
        ], 'survey-config');
        $this->publishes([
            __DIR__.'/../views' => resource_path('views/vendor/survey'),
        ], 'survey-views');
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'survey-migrations');
    }
}
