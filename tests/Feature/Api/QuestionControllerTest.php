<?php

namespace Sniper7Kills\Survey\Tests\Feature\Api;

use Carbon\Carbon;
use Sniper7Kills\Survey\Models\Option;
use Sniper7Kills\Survey\Models\Question;
use Sniper7Kills\Survey\Models\Survey;
use Sniper7Kills\Survey\Tests\FakeUser;
use Sniper7Kills\Survey\Tests\TestCase;

class QuestionControllerTest extends TestCase
{
    public function test_controller_show_returns_json()
    {
        $survey = factory(Survey::class)->create(['available_at'=>Carbon::now()]);
        $question = factory(Question::class)->make();
        $survey->questions()->save($question);

        $this->json('get',route('survey.api.question.show',['question'=>$question]))
            ->assertStatus(200)
            ->assertJson([
                'data'=>
                    [
                        'id' => $question->id,
                        'question' => $question->question
                    ]
            ]);
    }

    public function test_guests_can_not_view_questions_of_non_available_surveys()
    {
        $survey = factory(Survey::class)->create(['available_at'=>Carbon::now()->addHour()]);
        $question = factory(Question::class)->make();
        $survey->questions()->save($question);

        $this->json('get',route('survey.api.question.show',['question'=>$question]))
            ->assertStatus(403);
    }

    public function test_users_can_not_view_questions_of_non_available_surveys()
    {
        $admin = new FakeUser();
        $admin->save();
        $user = new FakeUser();
        $user->save();
        $this->actingAs($user);

        $survey = factory(Survey::class)->create(['available_at'=>Carbon::now()->addHour()]);
        $question = factory(Question::class)->make();
        $survey->questions()->save($question);

        $this->json('get',route('survey.api.question.show',['question'=>$question]))
            ->assertStatus(403);
    }

    public function test_admins_can_view_questions_of_non_available_surveys()
    {
        $admin = new FakeUser();
        $admin->save();
        $this->actingAs($admin);

        $survey = factory(Survey::class)->create(['available_at'=>Carbon::now()->addHour()]);
        $question = factory(Question::class)->make();
        $survey->questions()->save($question);

        $this->json('get',route('survey.api.question.show',['question'=>$question]))
            ->assertStatus(200);
    }

    public function test_guests_can_not_view_questions_of_ended_surveys()
    {
        $survey = factory(Survey::class)->create(['available_until'=>Carbon::now()->subHour()]);
        $question = factory(Question::class)->make();
        $survey->questions()->save($question);

        $this->json('get',route('survey.api.question.show',['question'=>$question]))
            ->assertStatus(403);
    }

    public function test_users_can_not_view_questions_of_ended_surveys()
    {
        $admin = new FakeUser();
        $admin->save();
        $user = new FakeUser();
        $user->save();
        $this->actingAs($user);

        $survey = factory(Survey::class)->create(['available_until'=>Carbon::now()->subHour()]);
        $question = factory(Question::class)->make();
        $survey->questions()->save($question);

        $this->json('get',route('survey.api.question.show',['question'=>$question]))
            ->assertStatus(403);
    }

    public function test_admins_can_view_questions_of_ended_surveys()
    {
        $admin = new FakeUser();
        $admin->save();
        $this->actingAs($admin);

        $survey = factory(Survey::class)->create(['available_until'=>Carbon::now()->subHour()]);
        $question = factory(Question::class)->make();
        $survey->questions()->save($question);

        $this->json('get',route('survey.api.question.show',['question'=>$question]))
            ->assertStatus(200);
    }
}