<?php


namespace Sniper7Kills\Survey\Tests\Unit;


use Illuminate\Support\Facades\Config;
use Sniper7Kills\Survey\Models\Option;
use Sniper7Kills\Survey\Models\Question;
use Sniper7Kills\Survey\Models\Survey;
use Sniper7Kills\Survey\Tests\TestCase;

class SurveySubmissionRulesTest extends TestCase
{
    public function test_survey_required_fields_are_required_or_return()
    {
        $survey = new Survey(['name'=>'Test Survey', 'description'=>'Simple Survey Test']);
        $survey->save();

        $question1 = new Question(['question'=>'Question 1', 'type'=>'text', 'required'=>true]);
        $survey->questions()->save($question1);

        $submitData = [];

        $root_path = Config::get('survey.root_path');
        $this->post('/'.$root_path.'/'.$survey->getSurveyIdentifier(),$submitData)
            ->assertStatus(302);
    }

    public function test_survey_select_must_be_valid_option_or_return()
    {
        $survey = new Survey(['name'=>'Test Survey', 'description'=>'Simple Survey Test']);
        $survey->save();

        $question1 = new Question(['question'=>'Question 1', 'type'=>'select', 'required'=>true]);
        $survey->questions()->save($question1);

        $option1 = new Option(['value'=>'valid Q1 option']);
        $question1->options()->save($option1);

        $question2 = new Question(['question'=>'Question 2', 'type'=>'select', 'required'=>false]);
        $survey->questions()->save($question2);

        $option2 = new Option(['value'=>'valid Q2 option']);
        $question2->options()->save($option2);

        $submitData = [
            $question1->id => $option2->id
        ];

        $root_path = Config::get('survey.root_path');
        $this->post('/'.$root_path.'/'.$survey->getSurveyIdentifier(),$submitData)
            ->assertStatus(302);
    }

    public function test_survey_select_must_be_valid_option()
    {
        $survey = new Survey(['name'=>'Test Survey', 'description'=>'Simple Survey Test']);
        $survey->save();

        $question1 = new Question(['question'=>'Question 1', 'type'=>'select', 'required'=>true]);
        $survey->questions()->save($question1);

        $option1 = new Option(['value'=>'valid Q1 option']);
        $question1->options()->save($option1);

        $submitData = [
            $question1->id => $option1->id
        ];

        $root_path = Config::get('survey.root_path');
        $this->post('/'.$root_path.'/'.$survey->getSurveyIdentifier(),$submitData)
            ->assertStatus(200);
    }

    public function test_survey_radio_must_be_valid_option_or_return()
    {
        $survey = new Survey(['name'=>'Test Survey', 'description'=>'Simple Survey Test']);
        $survey->save();

        $question1 = new Question(['question'=>'Question 1', 'type'=>'radio', 'required'=>true]);
        $survey->questions()->save($question1);

        $option1 = new Option(['value'=>'valid Q1 option']);
        $question1->options()->save($option1);

        $question2 = new Question(['question'=>'Question 2', 'type'=>'radio', 'required'=>false]);
        $survey->questions()->save($question2);

        $option2 = new Option(['value'=>'valid Q2 option']);
        $question2->options()->save($option2);

        $submitData = [
            $question1->id => $option2->id
        ];

        $root_path = Config::get('survey.root_path');
        $this->post('/'.$root_path.'/'.$survey->getSurveyIdentifier(),$submitData)
            ->assertStatus(302);
    }

    public function test_survey_radio_must_be_valid_option()
    {
        $survey = new Survey(['name'=>'Test Survey', 'description'=>'Simple Survey Test']);
        $survey->save();

        $question1 = new Question(['question'=>'Question 1', 'type'=>'radio', 'required'=>true]);
        $survey->questions()->save($question1);

        $option1 = new Option(['value'=>'valid Q1 option']);
        $question1->options()->save($option1);

        $submitData = [
            $question1->id => $option1->id
        ];

        $root_path = Config::get('survey.root_path');
        $this->post('/'.$root_path.'/'.$survey->getSurveyIdentifier(),$submitData)
            ->assertStatus(200);
    }
    public function test_survey_checkbox_must_be_an_array_or_return()
    {
        $survey = new Survey(['name'=>'Test Survey', 'description'=>'Simple Survey Test']);
        $survey->save();

        $question1 = new Question(['question'=>'Question 1', 'type'=>'checkbox', 'required'=>true]);
        $survey->questions()->save($question1);

        $option1 = new Option(['value'=>'valid Q1 option']);
        $question1->options()->save($option1);


        $submitData = [
            $question1->id => $option1->id
        ];

        $root_path = Config::get('survey.root_path');
        $this->post('/'.$root_path.'/'.$survey->getSurveyIdentifier(),$submitData)
            ->assertStatus(302);
    }

    public function test_survey_checkbox_must_be_an_array()
    {
        $survey = new Survey(['name'=>'Test Survey', 'description'=>'Simple Survey Test']);
        $survey->save();

        $question1 = new Question(['question'=>'Question 1', 'type'=>'checkbox', 'required'=>true]);
        $survey->questions()->save($question1);

        $option1 = new Option(['value'=>'valid Q1 option']);
        $question1->options()->save($option1);

        $submitData = [
            $question1->id => [$option1->id]
        ];

        $root_path = Config::get('survey.root_path');
        $this->post('/'.$root_path.'/'.$survey->getSurveyIdentifier(),$submitData)
            ->assertStatus(200);
    }

    public function test_survey_checkbox_must_be_valid_option_or_return()
    {
        $survey = new Survey(['name'=>'Test Survey', 'description'=>'Simple Survey Test']);
        $survey->save();

        $question1 = new Question(['question'=>'Question 1', 'type'=>'checkbox', 'required'=>true]);
        $survey->questions()->save($question1);

        $option1 = new Option(['value'=>'valid Q1 option']);
        $question1->options()->save($option1);

        $question2 = new Question(['question'=>'Question 2', 'type'=>'checkbox', 'required'=>false]);
        $survey->questions()->save($question2);

        $option2 = new Option(['value'=>'valid Q2 option']);
        $question2->options()->save($option2);

        $submitData = [
            $question1->id => [$option2->id]
        ];

        $root_path = Config::get('survey.root_path');
        $this->post('/'.$root_path.'/'.$survey->getSurveyIdentifier(),$submitData)
            ->assertStatus(302);
    }

    public function test_survey_checkbox_must_be_valid_option()
    {
        $survey = new Survey(['name'=>'Test Survey', 'description'=>'Simple Survey Test']);
        $survey->save();

        $question1 = new Question(['question'=>'Question 1', 'type'=>'checkbox', 'required'=>true]);
        $survey->questions()->save($question1);

        $option1 = new Option(['value'=>'valid Q1 option']);
        $question1->options()->save($option1);

        $submitData = [
            $question1->id => [$option1->id]
        ];

        $root_path = Config::get('survey.root_path');
        $this->post('/'.$root_path.'/'.$survey->getSurveyIdentifier(),$submitData)
            ->assertStatus(200);
    }
}