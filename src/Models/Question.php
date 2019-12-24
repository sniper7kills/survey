<?php
namespace Sniper7Kills\Survey\Models;

class Question extends \Sniper7Kills\Survey\Models\AbstractModels\AbstractQuestion
{
    protected $with = ['options'];

    public function options()
    {
        return parent::options()->orderBy('order');
    }
}
