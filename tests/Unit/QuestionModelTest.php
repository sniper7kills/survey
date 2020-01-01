<?php

namespace Sniper7Kills\Survey\Tests\Unit;

use Sniper7Kills\Survey\Models\Option;
use Sniper7Kills\Survey\Models\Question;
use Sniper7Kills\Survey\Models\Survey;
use Sniper7Kills\Survey\Tests\TestCase;

class QuestionModelTest extends TestCase
{
    public function test_question_has_id_on_create()
    {
        $survey = factory(Survey::class)->create();
        $question = factory(Question::class)->make();
        $survey->questions()->save($question);

        $this->assertNotNull($question->id);
    }

    public function test_options_are_returned_in_order()
    {
        $survey = factory(Survey::class)->create();
        $question = factory(Question::class)->make();
        $survey->questions()->save($question);

        $option1 = factory(Option::class)->make(['order'=>4]);
        $question->options()->save($option1);
        $option2 = factory(Option::class)->make(['order'=>3]);
        $question->options()->save($option2);
        $option3 = factory(Option::class)->make(['order'=>2]);
        $question->options()->save($option3);
        $option4 = factory(Option::class)->make(['order'=>1]);
        $question->options()->save($option4);

        $this->assertEquals($option4->value,$question->options[0]->value);
        $this->assertEquals($option3->value,$question->options[1]->value);
        $this->assertEquals($option2->value,$question->options[2]->value);
        $this->assertEquals($option1->value,$question->options[3]->value);
    }

    public function test_options_are_returned_with_null_order_prior_to_ordered()
    {
        $survey = factory(Survey::class)->create();
        $question = factory(Question::class)->make();
        $survey->questions()->save($question);

        $option1 = factory(Option::class)->make(['order'=>3]);
        $question->options()->save($option1);
        $option2 = factory(Option::class)->make();
        $question->options()->save($option2);
        $option3 = factory(Option::class)->make(['order'=>2]);
        $question->options()->save($option3);
        $option4 = factory(Option::class)->make(['order'=>1]);
        $question->options()->save($option4);

        $this->assertEquals($option2->value,$question->options[0]->value);
        $this->assertEquals($option4->value,$question->options[1]->value);
        $this->assertEquals($option3->value,$question->options[2]->value);
        $this->assertEquals($option1->value,$question->options[3]->value);
    }

    public function test_options_with_same_order_are_returned_alphabetically()
    {
        $survey = factory(Survey::class)->create();
        $question = factory(Question::class)->make();
        $survey->questions()->save($question);

        $option1 = factory(Option::class)->make(['value'=>'f']);
        $question->options()->save($option1);
        $option2 = factory(Option::class)->make(['value'=>'e']);
        $question->options()->save($option2);
        $option3 = factory(Option::class)->make(['value'=>'d','order'=>1]);
        $question->options()->save($option3);
        $option4 = factory(Option::class)->make(['value'=>'c','order'=>1]);
        $question->options()->save($option4);

        $this->assertEquals('e',$question->options[0]->value);
        $this->assertEquals('f',$question->options[1]->value);
        $this->assertEquals('c',$question->options[2]->value);
        $this->assertEquals('d',$question->options[3]->value);
    }
}