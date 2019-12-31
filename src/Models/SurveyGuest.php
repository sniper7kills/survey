<?php
namespace Sniper7Kills\Survey\Models;

class SurveyGuest extends \Sniper7Kills\Survey\Models\AbstractModels\AbstractSurveyGuest
{
    public static function current()
    {
        return SurveyGuest::firstOrCreate([
            'ip' => request()->getClientIp(),
            'agent' => request()->header('User-Agent')
        ]);
    }
}
