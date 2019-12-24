<?php
namespace Sniper7Kills\Survey\Models;

class Survey extends \Sniper7Kills\Survey\Models\AbstractModels\AbstractSurvey
{
    protected $with = ['questions'];
}
