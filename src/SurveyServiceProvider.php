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
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->mergeConfigFrom(
            __DIR__.'/../config/Survey.php', 'Survey'
        );

        //$this->loadViewsFrom(__DIR__.'/views', 'todolist');
        //$this->publishes([
        //    __DIR__.'/views' => base_path('resources/views/wisdmlabs/todolist'),
        //]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../views', 'survey');
        $this->publishes([
            __DIR__.'/../config/Survey.php' => config_path('Survey.php'),
            __DIR__.'/../database/migrations/' => database_path('migrations'),
            __DIR__.'/../views' => resource_path('views/vendor/survey'),
        ]);

        $this->app->make('Sniper7Kills\Survey\Controllers\SurveyController');
    }
}
