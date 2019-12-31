<?php


namespace Sniper7Kills\Survey\Tests\Feature;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Sniper7Kills\Survey\Models\Option;
use Sniper7Kills\Survey\Models\Question;
use Sniper7Kills\Survey\Models\Survey;
use Sniper7Kills\Survey\Tests\TestCase;

class SurveyViewTest extends TestCase
{
    public function test_survey_is_visible()
    {
        $survey = new Survey(['name'=>'Test Survey', 'description'=>'Simple Survey Test']);
        $survey->save();

        $root_path = Config::get('survey.root_path');
        $this->get('/'.$root_path.'/'.$survey->getSurveyIdentifier())
            ->assertStatus(200)
            ->assertSee('Test Survey')
            ->assertSee('Simple Survey Test');
    }

    public function test_private_survey_is_unavailable_to_guests()
    {
        $survey = new Survey(['name'=>'Test Private Survey', 'description'=>'Simple Private Survey Test', 'guests'=>false]);
        $survey->save();

        $root_path = Config::get('survey.root_path');
        $this->get('/'.$root_path.'/'.$survey->getSurveyIdentifier())
            ->assertStatus(403);
    }

    public function test_private_survey_is_available_to_non_guests()
    {
        $survey = new Survey(['name'=>'Test Private Survey', 'description'=>'Simple Private Survey Test', 'guests'=>false]);
        $survey->save();

        $this->actingAs(new SurveyViewTestFakeUser());
        $root_path = Config::get('survey.root_path');
        $this->get('/'.$root_path.'/'.$survey->getSurveyIdentifier())
            ->assertStatus(200);
    }

    public function test_survey_questions_are_visible()
    {
        $survey = new Survey(['name'=>'Test Survey', 'description'=>'Simple Survey Test']);
        $survey->save();

        $question1 = new Question(['question'=>'Question 1', 'type'=>'text']);
        $survey->questions()->save($question1);
        $question2 = new Question(['question'=>'Question 2', 'type'=>'radio']);
        $survey->questions()->save($question2);
        $question3 = new Question(['question'=>'Question 3', 'type'=>'checkbox']);
        $survey->questions()->save($question3);
        $question4 = new Question(['question'=>'Question 4', 'type'=>'select']);
        $survey->questions()->save($question4);

        $root_path = Config::get('survey.root_path');
        $this->get('/'.$root_path.'/'.$survey->getSurveyIdentifier())
            ->assertStatus(200)
            ->assertSee('Question 1')
            ->assertSee('Question 2')
            ->assertSee('Question 3')
            ->assertSee('Question 4');
    }

    public function test_survey_question_creates_textarea()
    {
        $survey = new Survey(['name'=>'Test Survey', 'description'=>'Simple Survey Test']);
        $survey->save();

        $question1 = new Question(['question'=>'Question 1', 'type'=>'text']);
        $survey->questions()->save($question1);

        $root_path = Config::get('survey.root_path');
        $this->get('/'.$root_path.'/'.$survey->getSurveyIdentifier())
            ->assertStatus(200)
            ->assertSee('Question 1')
            ->assertSee('textarea name="'.$question1->id.'"');
    }

    public function test_survey_question_creates_radio()
    {
        $survey = new Survey(['name'=>'Test Survey', 'description'=>'Simple Survey Test']);
        $survey->save();

        $question1 = new Question(['question'=>'Question 1', 'type'=>'radio']);
        $survey->questions()->save($question1);
        $option1 = new Option(['value'=>'Option 1']);
        $question1->options()->save($option1);

        $root_path = Config::get('survey.root_path');
        $this->get('/'.$root_path.'/'.$survey->getSurveyIdentifier())
            ->assertStatus(200)
            ->assertSee('Question 1')
            ->assertSee('Option 1')
            ->assertSee('type="radio" name="'.$question1->id.'" value="'.$option1->id.'"');
    }

    public function test_survey_question_creates_checkbox()
    {
        $survey = new Survey(['name'=>'Test Survey', 'description'=>'Simple Survey Test']);
        $survey->save();

        $question1 = new Question(['question'=>'Question 1', 'type'=>'checkbox']);
        $survey->questions()->save($question1);
        $option1 = new Option(['value'=>'Option 1']);
        $question1->options()->save($option1);

        $root_path = Config::get('survey.root_path');
        $this->get('/'.$root_path.'/'.$survey->getSurveyIdentifier())
            ->assertStatus(200)
            ->assertSee('Question 1')
            ->assertSee('Option 1')
            ->assertSee('type="checkbox" name="'.$question1->id.'[]" value="'.$option1->id.'"');
    }

    public function test_survey_question_creates_select()
    {
        $survey = new Survey(['name'=>'Test Survey', 'description'=>'Simple Survey Test']);
        $survey->save();

        $question1 = new Question(['question'=>'Question 1', 'type'=>'select']);
        $survey->questions()->save($question1);
        $option1 = new Option(['value'=>'Option 1']);
        $question1->options()->save($option1);

        $root_path = Config::get('survey.root_path');
        $this->get('/'.$root_path.'/'.$survey->getSurveyIdentifier())
            ->assertStatus(200)
            ->assertSee('Question 1')
            ->assertSee('Option 1')
            ->assertSee('select name="'.$question1->id.'"')
            ->assertSee('option value="'.$option1->id.'"');
    }

    public function test_users_can_not_view_survey_they_already_submitted()
    {
        $survey = new Survey(['name'=>'Test Survey', 'description'=>'Simple Survey Test', 'guests'=>false]);
        $survey->save();

        $question1 = new Question(['question'=>'Question 1', 'type'=>'text']);
        $survey->questions()->save($question1);

        $submitData = [
            $question1->id => 'Sample Data'
        ];

        $user = new SurveyViewTestFakeUser();
        $user->save();
        $this->actingAs($user);
        $root_path = Config::get('survey.root_path');
        $this->post('/'.$root_path.'/'.$survey->getSurveyIdentifier(),$submitData)
            ->assertStatus(200);

        $this->assertCount(1,$survey->responses);

        $this->get('/'.$root_path.'/'.$survey->getSurveyIdentifier(),$submitData)
            ->assertStatus(403);

        $this->assertCount(1,$survey->responses);
    }

    public function test_guests_can_not_view_survey_they_already_submitted()
    {
        $survey = new Survey(['name'=>'Test Survey', 'description'=>'Simple Survey Test']);
        $survey->save();

        $question1 = new Question(['question'=>'Question 1', 'type'=>'text']);
        $survey->questions()->save($question1);

        $submitData = [
            $question1->id => 'Sample Data'
        ];

        $root_path = Config::get('survey.root_path');
        $this->post('/'.$root_path.'/'.$survey->getSurveyIdentifier(),$submitData)
            ->assertStatus(200);

        $this->assertCount(1,$survey->responses);

        $this->get('/'.$root_path.'/'.$survey->getSurveyIdentifier(),$submitData)
            ->assertStatus(403);

        $this->assertCount(1,$survey->responses);
    }

    public function test_survey_not_available_after_end_time()
    {
        $survey = new Survey(['name'=>'Test Survey', 'description'=>'Simple Survey Test', 'end_at' => Carbon::now()]);
        $survey->save();

        $root_path = Config::get('survey.root_path');
        $this->get('/'.$root_path.'/'.$survey->getSurveyIdentifier(),[])
            ->assertStatus(403);
    }

    public function test_survey_is_available_before_end_time()
    {
        $survey = new Survey(['name'=>'Test Survey', 'description'=>'Simple Survey Test', 'end_at' => Carbon::now()->addHour()]);
        $survey->save();

        $root_path = Config::get('survey.root_path');
        $this->get('/'.$root_path.'/'.$survey->getSurveyIdentifier(),[])
            ->assertStatus(200);
    }
}
class SurveyViewTestFakeUser extends Model implements \Illuminate\Contracts\Auth\Authenticatable
{
    protected $table = 'test-users';
    public $id = 1;
    public $rememberToken = null;

    /**
     * @inheritDoc
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * @inheritDoc
     */
    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    /**
     * @inheritDoc
     */
    public function getAuthPassword()
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getRememberToken()
    {
        return $this->{$this->getRememberTokenName()};
    }

    /**
     * @inheritDoc
     */
    public function setRememberToken($value)
    {
        $this->{$this->getRememberTokenName()} = $value;
    }

    /**
     * @inheritDoc
     */
    public function getRememberTokenName()
    {
        return 'rememberToken';
    }
}