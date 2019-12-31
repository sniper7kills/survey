<?php


namespace Sniper7Kills\Survey\Tests\Feature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Sniper7Kills\Survey\Models\Question;
use Sniper7Kills\Survey\Models\Survey;
use Sniper7Kills\Survey\Tests\TestCase;

class SurveySubmitTest extends TestCase
{
    public function test_public_survey_submissions_open_for_guests()
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
    }

    public function test_guests_can_not_submit_private_surveys()
    {
        $survey = new Survey(['name'=>'Test Survey', 'description'=>'Simple Survey Test', 'guests'=>false]);
        $survey->save();

        $question1 = new Question(['question'=>'Question 1', 'type'=>'text']);
        $survey->questions()->save($question1);

        $submitData = [
            $question1->id => 'Sample Data'
        ];

        $root_path = Config::get('survey.root_path');
        $this->post('/'.$root_path.'/'.$survey->getSurveyIdentifier(),$submitData)
            ->assertStatus(403);
    }

    public function test_users_can_submit_private_surveys()
    {
        $survey = new Survey(['name'=>'Test Survey', 'description'=>'Simple Survey Test', 'guests'=>false]);
        $survey->save();

        $question1 = new Question(['question'=>'Question 1', 'type'=>'text']);
        $survey->questions()->save($question1);

        $submitData = [
            $question1->id => 'Sample Data'
        ];

        $user = new SurveySubmitTestFakeUser();
        $user->save();
        $this->actingAs($user);
        $root_path = Config::get('survey.root_path');
        $this->post('/'.$root_path.'/'.$survey->getSurveyIdentifier(),$submitData)
            ->assertStatus(200);
    }

    public function test_guest_submissions_are_saved()
    {
        $survey = new Survey(['name'=>'Test Survey', 'description'=>'Simple Survey Test', 'guests'=>true]);
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
    }

    public function test_users_submissions_are_saved()
    {
        $survey = new Survey(['name'=>'Test Survey', 'description'=>'Simple Survey Test', 'guests'=>false]);
        $survey->save();

        $question1 = new Question(['question'=>'Question 1', 'type'=>'text']);
        $survey->questions()->save($question1);

        $submitData = [
            $question1->id => 'Sample Data'
        ];

        $user = new SurveySubmitTestFakeUser();
        $user->save();
        $this->actingAs($user);
        $root_path = Config::get('survey.root_path');
        $this->post('/'.$root_path.'/'.$survey->getSurveyIdentifier(),$submitData)
            ->assertStatus(200);

        $this->assertCount(1,$survey->responses);
    }

    public function test_users_can_not_submit_same_survey_twice()
    {
        $survey = new Survey(['name'=>'Test Survey', 'description'=>'Simple Survey Test', 'guests'=>false]);
        $survey->save();

        $question1 = new Question(['question'=>'Question 1', 'type'=>'text']);
        $survey->questions()->save($question1);

        $submitData = [
            $question1->id => 'Sample Data'
        ];

        $user = new SurveySubmitTestFakeUser();
        $user->save();
        $this->actingAs($user);
        $root_path = Config::get('survey.root_path');
        $this->post('/'.$root_path.'/'.$survey->getSurveyIdentifier(),$submitData)
            ->assertStatus(200);

        $this->assertCount(1,$survey->responses);

        $this->post('/'.$root_path.'/'.$survey->getSurveyIdentifier(),$submitData)
            ->assertStatus(403);

        $this->assertCount(1,$survey->responses);
    }

    public function test_guests_can_not_submit_same_survey_twice()
    {
        $survey = new Survey(['name'=>'Test guests can not submit same survey twice', 'description'=>'Simple Survey Test']);
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

        $this->post('/'.$root_path.'/'.$survey->getSurveyIdentifier(),$submitData)
            ->assertStatus(403);

        $this->assertCount(1,$survey->responses);
    }
}
class SurveySubmitTestFakeUser extends Model implements \Illuminate\Contracts\Auth\Authenticatable
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