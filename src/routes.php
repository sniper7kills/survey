<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

Route::namespace('\\Sniper7Kills\\Survey\\Controllers')
    ->middleware(['web','bindings'])
    ->prefix(Config::get('survey.root_path','survey'))
    ->name('survey.')
    ->group(function(){
        Route::get("/{survey}","SurveyController@view")->name('view');
        Route::post("/{survey}","SurveyController@submit")->name('submit');
    });