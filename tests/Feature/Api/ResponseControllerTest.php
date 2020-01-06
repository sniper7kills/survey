<?php

namespace Sniper7Kills\Survey\Tests\Feature\Api;

use Carbon\Carbon;
use Sniper7Kills\Survey\Models\Response;
use Sniper7Kills\Survey\Models\Survey;
use Sniper7Kills\Survey\Models\SurveyGuest;
use Sniper7Kills\Survey\Tests\FakeUser;
use Sniper7Kills\Survey\Tests\TestCase;

class ResponseControllerTest extends TestCase
{
    public function test_guests_can_not_view_index()
    {
        $admin = new FakeUser();
        $admin->save();
        $user = new FakeUser();
        $user->save();

        $survey = factory(Survey::class)->create(['available_at'=>Carbon::now()]);
        $response = factory(Response::class)->make();

        $response->userable()->associate($user);
        $survey->responses()->save($response);

        $this->json('get', route('survey.api.responses.index'))
            ->assertStatus(403);
    }

    public function test_users_can_not_view_index()
    {
        $admin = new FakeUser();
        $admin->save();
        $user = new FakeUser();
        $user->save();

        $survey = factory(Survey::class)->create(['available_at'=>Carbon::now()]);
        $response = factory(Response::class)->make();

        $response->userable()->associate($user);
        $survey->responses()->save($response);

        $this->actingAs($user);

        $this->json('get', route('survey.api.responses.index'))
            ->assertStatus(403);
    }

    public function test_admins_can_view_index()
    {
        $admin = new FakeUser();
        $admin->save();
        $user = new FakeUser();
        $user->save();

        $survey = factory(Survey::class)->create(['available_at'=>Carbon::now()]);
        $response = factory(Response::class)->make();

        $response->userable()->associate($user);
        $survey->responses()->save($response);

        $this->actingAs($admin);

        $this->json('get', route('survey.api.responses.index'))
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => $response->id
                    ]
                ]
            ]);
    }

    public function test_guests_can_not_view_show()
    {
        $admin = new FakeUser();
        $admin->save();
        $user = new FakeUser();
        $user->save();

        $survey = factory(Survey::class)->create(['available_at'=>Carbon::now()]);
        $response = factory(Response::class)->make();

        $response->userable()->associate($user);
        $survey->responses()->save($response);

        $this->json('get', route('survey.api.responses.show',['response' => $response]))
            ->assertStatus(403);
    }

    public function test_users_can_not_view_show()
    {
        $admin = new FakeUser();
        $admin->save();
        $user = new FakeUser();
        $user->save();

        $survey = factory(Survey::class)->create(['available_at'=>Carbon::now()]);
        $response = factory(Response::class)->make();

        $response->userable()->associate($user);
        $survey->responses()->save($response);

        $this->actingAs($user);

        $this->json('get', route('survey.api.responses.show',['response' => $response]))
            ->assertStatus(403);
    }

    public function test_admins_can_view_show()
    {
        $admin = new FakeUser();
        $admin->save();
        $user = new FakeUser();
        $user->save();

        $survey = factory(Survey::class)->create(['available_at'=>Carbon::now()]);
        $response = factory(Response::class)->make();

        $response->userable()->associate($user);
        $survey->responses()->save($response);

        $this->actingAs($admin);

        $this->json('get', route('survey.api.responses.show',['response' => $response]))
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $response->id
                ]
            ]);
    }

    public function test_guests_can_not_delete_responses()
    {
        $admin = new FakeUser();
        $admin->save();
        $user = new FakeUser();
        $user->save();

        $survey = factory(Survey::class)->create(['available_at'=>Carbon::now()]);
        $response = factory(Response::class)->make();

        $response->userable()->associate($user);
        $survey->responses()->save($response);

        $this->json('delete', route('survey.api.responses.destroy',['response' => $response]))
            ->assertStatus(403);

        $this->assertCount(1,Response::all());
    }

    public function test_users_can_not_delete_responses()
    {
        $admin = new FakeUser();
        $admin->save();
        $user = new FakeUser();
        $user->save();

        $survey = factory(Survey::class)->create(['available_at'=>Carbon::now()]);
        $response = factory(Response::class)->make();

        $response->userable()->associate($user);
        $survey->responses()->save($response);

        $this->actingAs($user);

        $this->json('delete', route('survey.api.responses.destroy',['response' => $response]))
            ->assertStatus(403);

        $this->assertCount(1,Response::all());
    }

    public function test_admins_can_delete_responses()
    {
        $admin = new FakeUser();
        $admin->save();
        $user = new FakeUser();
        $user->save();

        $survey = factory(Survey::class)->create(['available_at'=>Carbon::now()]);
        $response = factory(Response::class)->make();

        $response->userable()->associate($user);
        $survey->responses()->save($response);

        $this->actingAs($admin);

        $this->json('delete', route('survey.api.responses.destroy',['response' => $response]))
            ->assertStatus(204);

        $this->assertCount(0,Response::all());
    }

    public function test_responses_can_not_be_created_for_unpublished_surveys()
    {
        $survey = factory(Survey::class)->create(['available_at'=>Carbon::now()->addHour()]);

        $this->json('post', route('survey.api.responses.store'), [
            'survey_id' => $survey->id
        ])
            ->assertStatus(403);
    }

    public function test_responses_can_not_be_created_for_ended_surveys()
    {
        $survey = factory(Survey::class)->create(['available_at'=>Carbon::now(), 'available_until'=>Carbon::now()->subHour()]);

        $this->json('post', route('survey.api.responses.store'), [
            'survey_id' => $survey->id
        ])
            ->assertStatus(403);
    }

    public function test_responses_can_be_created_by_guests()
    {
        $survey = factory(Survey::class)->create(['available_at'=>Carbon::now()->subHour(), 'available_until'=>Carbon::now()->addHour()]);

        $this->json('post', route('survey.api.responses.store'), [
            'survey_id' => $survey->id
        ])
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'id' => Response::first()->id,
                    'user' => [
                        'guest' => true,
                        'id' => SurveyGuest::first()->getAuthIdentifier()
                    ]
                ]
            ]);

        $this->assertCount(1, Response::all());
    }

    public function test_responses_can_be_created_by_users()
    {
        $admin = new FakeUser();
        $admin->save();
        $user = new FakeUser();
        $user->save();

        $survey = factory(Survey::class)->create(['available_at'=>Carbon::now()->subHour(), 'available_until'=>Carbon::now()->addHour()]);

        $this->actingAs($user);

        $this->json('post', route('survey.api.responses.store'), [
            'survey_id' => $survey->id
        ])
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'id' => Response::first()->id,
                    'user' => [
                        'guest' => false,
                        'type' => $admin->getMorphClass(),
                        'id' => $user->getAuthIdentifier()
                    ]
                ]
            ]);

        $this->assertCount(1, Response::all());
    }

    public function test_responses_can_be_created_by_admins()
    {
        $admin = new FakeUser();
        $admin->save();
        $user = new FakeUser();
        $user->save();

        $survey = factory(Survey::class)->create(['available_at'=>Carbon::now()->subHour(), 'available_until'=>Carbon::now()->addHour()]);

        $this->actingAs($admin);

        $this->json('post', route('survey.api.responses.store'), [
            'survey_id' => $survey->id
        ])
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'id' => Response::first()->id,
                    'user' => [
                        'guest' => false,
                        'type' => $admin->getMorphClass(),
                        'id' => $admin->getAuthIdentifier()
                    ]
                ]
            ]);

        $this->assertCount(1, Response::all());
    }

    public function test_responses_can_not_be_created_by_guests_for_private_surveys()
    {
        $survey = factory(Survey::class)->create(['guests' => false, 'available_at'=>Carbon::now()->subHour(), 'available_until'=>Carbon::now()->addHour()]);

        $this->json('post', route('survey.api.responses.store'), [
            'survey_id' => $survey->id
        ])
            ->assertStatus(403);

        $this->assertCount(0, Response::all());
    }

    public function test_responses_can_not_be_created_for_invalid_survey_ids()
    {
        $survey = factory(Survey::class)->create(['available_at'=>Carbon::now()->subHour(), 'available_until'=>Carbon::now()->addHour()]);

        $this->json('post', route('survey.api.responses.store'), [
            'survey_id' => 'abc-123-123'
        ])
            ->assertStatus(422);

        $this->assertCount(0, Response::all());
    }
}