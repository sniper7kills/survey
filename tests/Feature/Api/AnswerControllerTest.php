<?php

namespace Sniper7Kills\Survey\Tests\Feature\Api;

use Carbon\Carbon;
use Sniper7Kills\Survey\Models\Answer;
use Sniper7Kills\Survey\Models\Option;
use Sniper7Kills\Survey\Models\Question;
use Sniper7Kills\Survey\Models\Response;
use Sniper7Kills\Survey\Models\Survey;
use Sniper7Kills\Survey\Models\SurveyGuest;
use Sniper7Kills\Survey\Tests\FakeUser;
use Sniper7Kills\Survey\Tests\TestCase;

class AnswerControllerTest extends TestCase
{
    public function test_guests_can_not_view_show()
    {
        $admin = new FakeUser();
        $admin->save();
        $user = new FakeUser();
        $user->save();

        $survey = factory(Survey::class)->create(['available_at'=>Carbon::now()]);
        $question = factory(Question::class)->make();
        $survey->questions()->save($question);

        $response = factory(Response::class)->make();
        $response->userable()->associate($user);
        $survey->responses()->save($response);

        $answer = factory(Answer::class)->make();
        $answer->question()->associate($question);
        $response->answers()->save($answer);

        $this->json('get', route('survey.api.answers.show',['answer'=>$answer]))
            ->assertStatus(403);
    }

    public function test_users_can_not_view_show()
    {
        $admin = new FakeUser();
        $admin->save();
        $user = new FakeUser();
        $user->save();

        $survey = factory(Survey::class)->create(['available_at'=>Carbon::now()]);
        $question = factory(Question::class)->make();
        $survey->questions()->save($question);

        $response = factory(Response::class)->make();
        $response->userable()->associate($user);
        $survey->responses()->save($response);

        $answer = factory(Answer::class)->make();
        $answer->question()->associate($question);
        $response->answers()->save($answer);

        $this->actingAs($user);
        $this->json('get', route('survey.api.answers.show',['answer'=>$answer]))
            ->assertStatus(403);
    }

    public function test_admins_can_view_show_with_text_input()
    {
        $admin = new FakeUser();
        $admin->save();
        $user = new FakeUser();
        $user->save();

        $survey = factory(Survey::class)->create(['available_at'=>Carbon::now()]);
        $question = factory(Question::class)->make();
        $survey->questions()->save($question);

        $response = factory(Response::class)->make();
        $response->userable()->associate($user);
        $survey->responses()->save($response);

        $answer = factory(Answer::class)->make();
        $answer->question()->associate($question);
        $response->answers()->save($answer);

        $this->actingAs($admin);
        $this->json('get', route('survey.api.answers.show',['answer'=>$answer]))
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $answer->id,
                    'question' => $question->id,
                    'answer' => $answer->answer
                ]
            ]);
    }

    public function test_admins_can_view_show_with_single_option_input()
    {
        $admin = new FakeUser();
        $admin->save();
        $user = new FakeUser();
        $user->save();

        $survey = factory(Survey::class)->create(['available_at'=>Carbon::now()]);
        $question = factory(Question::class)->make(['type'=>'radio']);
        $survey->questions()->save($question);
        $option = factory(Option::class)->make();
        $question->options()->save($option);

        $response = factory(Response::class)->make();
        $response->userable()->associate($user);
        $survey->responses()->save($response);

        $answer = factory(Answer::class)->make(['answer'=>null]);
        $answer->question()->associate($question);
        $response->answers()->save($answer);
        $answer->options()->attach($option);

        $this->actingAs($admin);
        $this->json('get', route('survey.api.answers.show',['answer'=>$answer]))
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $answer->id,
                    'question' => $question->id,
                    'options' => [$option->id]
                ]
            ]);
    }

    public function test_admins_can_view_show_with_multiple_option_input()
    {
        $admin = new FakeUser();
        $admin->save();
        $user = new FakeUser();
        $user->save();

        $survey = factory(Survey::class)->create(['available_at'=>Carbon::now()]);
        $question = factory(Question::class)->make(['type'=>'radio']);
        $survey->questions()->save($question);
        $option = factory(Option::class)->make();
        $question->options()->save($option);
        $option2 = factory(Option::class)->make();
        $question->options()->save($option2);

        $response = factory(Response::class)->make();
        $response->userable()->associate($user);
        $survey->responses()->save($response);

        $answer = factory(Answer::class)->make(['answer'=>null]);
        $answer->question()->associate($question);
        $response->answers()->save($answer);
        $answer->options()->attach($option);
        $answer->options()->attach($option2);

        $this->actingAs($admin);
        $options = [$option->id, $option2->id];
        $this->json('get', route('survey.api.answers.show',['answer'=>$answer]))
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $answer->id,
                    'question' => $question->id,
                    'options' => sort($options)
                ]
            ]);
    }

    public function test_answers_can_be_submitted()
    {
        $user = new FakeUser();
        $user->save();
        $survey = factory(Survey::class)->create();
        $question = factory(Question::class)->make();
        $survey->questions()->save($question);
        $response = factory(Response::class)->make();
        $response->userable()->associate($user);
        $survey->responses()->save($response);

        $this->actingAs($user);

        $response = $this->json('post', route('survey.api.answers.store'), [
            'response_id' => $response->id,
            'question_id' => $question->id,
            'answer' => 'some answer'
        ])
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'question' => $question->id,
                    'answer' => "some answer"
                ]
            ]);

        $this->assertCount(1,Answer::all());
    }

    public function test_response_must_exist()
    {
        $survey = factory(Survey::class)->create();
        $question = factory(Question::class)->make();
        $survey->questions()->save($question);

        $this->json('post', route('survey.api.answers.store'), [
            'response_id' => 'abc-123-123',
            'question_id' => $question->id,
            'answer' => 'something'
        ])
            ->assertStatus(422);
    }

    public function test_response_belong_to_submitter()
    {
        $user = new FakeUser();
        $user->save();
        $survey = factory(Survey::class)->create();
        $question = factory(Question::class)->make();
        $survey->questions()->save($question);
        $response = factory(Response::class)->make();
        $response->userable()->associate($user);
        $survey->responses()->save($response);

        $this->json('post', route('survey.api.answers.store'), [
            'response_id' => $response->id,
            'question_id' => $question->id,
            'answer' => 'some answer'
        ])
            ->assertStatus(403);
    }

    public function test_survey_can_not_be_unpublished()
    {
        $user = new FakeUser();
        $user->save();
        $survey = factory(Survey::class)->create(['available_at'=>null]);
        $question = factory(Question::class)->make();
        $survey->questions()->save($question);
        $response = factory(Response::class)->make();
        $response->userable()->associate($user);
        $survey->responses()->save($response);

        $this->actingAs($user);

        $this->json('post', route('survey.api.answers.store'), [
            'response_id' => $response->id,
            'question_id' => $question->id,
            'answer' => 'some answer'
        ])
            ->assertStatus(403);
    }

    public function test_survey_can_not_be_closed()
    {
        $user = new FakeUser();
        $user->save();
        $survey = factory(Survey::class)->create(['available_until'=>Carbon::now()->subHour()]);
        $question = factory(Question::class)->make();
        $survey->questions()->save($question);
        $response = factory(Response::class)->make();
        $response->userable()->associate($user);
        $survey->responses()->save($response);

        $this->actingAs($user);

        $this->json('post', route('survey.api.answers.store'), [
            'response_id' => $response->id,
            'question_id' => $question->id,
            'answer' => 'some answer'
        ])
            ->assertStatus(403);
    }

    public function test_answer_for_question_can_not_already_exist_when_submitting()
    {
        $user = new FakeUser();
        $user->save();
        $survey = factory(Survey::class)->create();
        $question = factory(Question::class)->make();
        $survey->questions()->save($question);
        $response = factory(Response::class)->make();
        $response->userable()->associate($user);
        $survey->responses()->save($response);

        $answer = factory(Answer::class)->make();
        $answer->question()->associate($question);
        $answer->answer = "some answer";
        $response->answers()->save($answer);

        $this->actingAs($user);

        $response = $this->json('post', route('survey.api.answers.store'), [
            'response_id' => $response->id,
            'question_id' => $question->id,
            'answer' => 'some other answer'
        ])
            ->assertStatus(403);
    }

    public function test_option_must_belong_to_question_being_submitted()
    {
        $user = new FakeUser();
        $user->save();
        $survey = factory(Survey::class)->create();
        $question = factory(Question::class)->make();
        $survey->questions()->save($question);
        $response = factory(Response::class)->make();
        $response->userable()->associate($user);
        $survey->responses()->save($response);

        $survey2 = factory(Survey::class)->create();
        $question2 = factory(Question::class)->make();
        $survey2->questions()->save($question2);
        $option = factory(Option::class)->make();
        $question2->options()->save($option);

        $this->actingAs($user);

        $response = $this->json('post', route('survey.api.answers.store'), [
            'response_id' => $response->id,
            'question_id' => $question->id,
            'options' => [$option->id]
        ])
            ->assertStatus(403);
    }

    public function test_question_must_belong_to_survey_being_completed()
    {
        $user = new FakeUser();
        $user->save();
        $survey = factory(Survey::class)->create();
        $question = factory(Question::class)->make();
        $survey->questions()->save($question);
        $response = factory(Response::class)->make();
        $response->userable()->associate($user);
        $survey->responses()->save($response);

        $survey2 = factory(Survey::class)->create();
        $question2 = factory(Question::class)->make();
        $survey2->questions()->save($question2);

        $this->actingAs($user);

        $response = $this->json('post', route('survey.api.answers.store'), [
            'response_id' => $response->id,
            'question_id' => $question2->id,
            'answer' => 'some answer'
        ])
            ->assertStatus(403);
    }
}