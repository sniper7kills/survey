<?php

namespace Sniper7Kills\Survey\Tests\Unit;

use Sniper7Kills\Survey\Models\Option;
use Sniper7Kills\Survey\Models\Question;
use Sniper7Kills\Survey\Models\Survey;
use Sniper7Kills\Survey\Tests\TestCase;

class OptionModelTest extends TestCase
{
    public function test_question_has_id_on_create()
    {
        $survey = factory(Survey::class)->create();
        $question = factory(Question::class)->make();
        $survey->questions()->save($question);
        $option = factory(Option::class)->make();
        $question->options()->save($option);

        $this->assertNotNull($option->id);
    }
}