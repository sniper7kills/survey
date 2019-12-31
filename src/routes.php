<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

Route::namespace('\\Sniper7Kills\\Survey\\Controllers')
    ->middleware(['web'])
    ->prefix(Config::get('survey.root_path','survey'))
    ->name('survey.')
    ->group(function(){
        /**
         * User Routes
         */
        Route::get("/{survey}","SurveyController@view")->name('view');
        Route::post("/{survey}","SurveyController@submit")->name('submit');

        /**
         * Admin Routes
         */
        Route::prefix('admin')
            ->name('admin.')
            ->middleware(Config::get('survey.middleware'))
            ->group(function(){
                Route::get("/dashboard","AdminController@dashboard")->name('dashboard');
            });
    });