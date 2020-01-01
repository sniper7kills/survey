<?php


namespace Sniper7Kills\Survey\Contract;


interface SurveyAdmin
{
    /**
     * Determines if the current model is a survey admin
     * @return boolean
     */
    public function isASurveyAdmin();
}