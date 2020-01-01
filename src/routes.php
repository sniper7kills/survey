<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Sniper7Kills\Survey\Middleware\SurveyAdminMiddleware;

/**
 * Web Routes
 */
Route::namespace('\\Sniper7Kills\\Survey\\Controllers')
    ->middleware(['web'])
    ->prefix(Config::get('survey.root_path','survey'))
    ->name('survey.')
    ->group(function(){
        /**
         * User Accessible Routes
         */

        /**
         * Admin Accessible Routes
         */
        Route::prefix('admin')
            ->name('admin.')
            ->middleware(SurveyAdminMiddleware::class)
            ->group(function(){

            });
    });
/**
 * Api Routes
 */
Route::namespace('\\Sniper7Kills\\Survey\\Controllers\\Api')
    ->middleware(['api'])
    ->prefix('api/'.Config::get('survey.root_path','survey'))
    ->name('survey.api.')
    ->group(function(){
        /**
         * User Accessible Routes
         */
        Route::apiResources([
            'survey' => 'SurveyController',
            'question' => 'QuestionController'
        ]);
        Route::resource('publishedSurvey','PublishedSurveyController')->only('store','destroy');
    });