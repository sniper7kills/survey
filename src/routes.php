<?php

/*
 * Web Routes
 */
Route::prefix('survey')
    ->name('survey.')
    ->namespace('\Sniper7Kills\Survey\Controllers')
    ->middleware('web')
    ->group(function () {
        Route::get('/{survey}', 'SurveyController@view')->name('view');
        Route::post('/{survey}', 'SurveyController@submit')->name('submit');
        Route::get('/{survey}/results', 'SurveyController@results')->name('results');
        Route::get('/{survey}/results/{question}', 'SurveyController@resultsQuestion')->name('results.question');
});