<?php

namespace Sniper7Kills\Survey\Tests\Unit;

use Illuminate\Support\Facades\Config;
use Sniper7Kills\Survey\Models\Question;
use Sniper7Kills\Survey\Models\Survey;
use Sniper7Kills\Survey\Tests\TestCase;

class SurveyModelTest extends TestCase
{
    public function test_survey_has_slug_and_id_created_on_create()
    {
        $survey = factory(Survey::class)->create();
        $this->assertNotNull($survey->id);
        $this->assertNotNull($survey->slug);
    }

    public function test_survey_has_url_created_on_create()
    {
        $survey = factory(Survey::class)->create();
        $this->assertNotNull($survey->url);
    }

    public function test_survey_url_can_be_set_by_config_to_id()
    {
        Config::set('survey.identifier','id');
        $survey = factory(Survey::class)->create();

        $this->assertEquals($survey->url, $survey->id);
    }

    public function test_survey_url_can_be_set_by_config_to_slug()
    {
        Config::set('survey.identifier','slug');
        $survey = factory(Survey::class)->create();

        $this->assertEquals($survey->url, $survey->slug);
    }

    public function test_survey_can_have_url_changed_to_id()
    {
        Config::set('survey.identifier','slug');
        $survey = factory(Survey::class)->create();
        $survey->setUrlAsId();

        $this->assertEquals($survey->url, $survey->id);
        $this->assertEquals(Survey::first()->url, $survey->id);
    }

    public function test_survey_can_have_url_changed_to_slug()
    {
        Config::set('survey.identifier','id');
        $survey = factory(Survey::class)->create();
        $survey->setUrlAsSlug();

        $this->assertEquals($survey->url, $survey->slug);
        $this->assertEquals(Survey::first()->url, $survey->slug);
    }

    public function test_survey_can_be_published()
    {
        $survey = factory(Survey::class)->create(['available_at'=>null]);
        $this->assertNull($survey->available_at);

        $survey->publishSurvey();

        $this->assertNotNull($survey->available_at);
        $this->assertNotNull(Survey::first()->available_at);
    }

    public function test_survey_can_be_unpublished()
    {
        $survey = factory(Survey::class)->create();
        $this->assertNotNull($survey->available_at);

        $survey->unpublishSurvey();

        $this->assertNull($survey->available_at);
        $this->assertNull(Survey::first()->available_at);
    }

    public function test_slug_is_updated_when_name_is_updated()
    {
        $survey = factory(Survey::class)->create();
        $survey->update(['name' => 'Test Survey']);

        $this->assertEquals('test-survey',Survey::first()->slug);
    }

    public function test_survey_returns_questions_in_order()
    {
        $survey = factory(Survey::class)->create();
        $question1 = factory(Question::class)->make(['order'=>4]);
        $survey->questions()->save($question1);
        $question2 = factory(Question::class)->make(['order'=>3]);
        $survey->questions()->save($question2);
        $question3 = factory(Question::class)->make(['order'=>2]);
        $survey->questions()->save($question3);
        $question4 = factory(Question::class)->make(['order'=>1]);
        $survey->questions()->save($question4);

        $this->assertEquals($question4->question, $survey->questions[0]->question);
        $this->assertEquals($question3->question, $survey->questions[1]->question);
        $this->assertEquals($question2->question, $survey->questions[2]->question);
        $this->assertEquals($question1->question, $survey->questions[3]->question);
    }

    public function test_survey_returns_questions_with_null_order_prior_to_ordered()
    {
        $survey = factory(Survey::class)->create();
        $question1 = factory(Question::class)->make(['order'=>3]);
        $survey->questions()->save($question1);
        $question2 = factory(Question::class)->make();
        $survey->questions()->save($question2);
        $question3 = factory(Question::class)->make(['order'=>2]);
        $survey->questions()->save($question3);
        $question4 = factory(Question::class)->make(['order'=>1]);
        $survey->questions()->save($question4);

        $this->assertEquals($question2->question, $survey->questions[0]->question);
        $this->assertEquals($question4->question, $survey->questions[1]->question);
        $this->assertEquals($question3->question, $survey->questions[2]->question);
        $this->assertEquals($question1->question, $survey->questions[3]->question);
    }

    public function test_survey_returns_questions_with_same_order_alphabetically()
    {
        $survey = factory(Survey::class)->create();
        $question1 = factory(Question::class)->make(['question'=>'f']);
        $survey->questions()->save($question1);
        $question2 = factory(Question::class)->make(['question'=>'e']);
        $survey->questions()->save($question2);
        $question3 = factory(Question::class)->make(['question'=>'d','order'=>1]);
        $survey->questions()->save($question3);
        $question4 = factory(Question::class)->make(['question'=>'c','order'=>1]);
        $survey->questions()->save($question4);

        $this->assertEquals('e', $survey->questions[0]->question);
        $this->assertEquals('f', $survey->questions[1]->question);
        $this->assertEquals('c', $survey->questions[2]->question);
        $this->assertEquals('d', $survey->questions[3]->question);
    }
}