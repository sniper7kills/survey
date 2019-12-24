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

/*
 * Api Routes
 */
Route::prefix('api/survey')
    ->name('api.survey.')
    ->namespace('\Sniper7Kills\Survey\Controllers\Api')
    ->middleware('api')
    ->group(function() {
        Route::apiResources([
            'answers' => 'AnswerController',
            'options' => 'OptionController',
            'questions' => 'QuestionController',
            'responses' => 'ResponseController',
            'surveys' => 'SurveyController',
        ]);
});