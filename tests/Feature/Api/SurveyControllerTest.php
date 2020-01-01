<?php

namespace Sniper7Kills\Survey\Tests\Feature\Api;

use Carbon\Carbon;
use Sniper7Kills\Survey\Models\Option;
use Sniper7Kills\Survey\Models\Question;
use Sniper7Kills\Survey\Models\Survey;
use Sniper7Kills\Survey\Tests\FakeUser;
use Sniper7Kills\Survey\Tests\TestCase;

class SurveyControllerTest extends TestCase
{
    public function test_controller_show_returns_json()
    {
        $survey = factory(Survey::class)->create();

        $this->json('get',route('survey.api.survey.show',['survey'=>$survey]))
            ->assertStatus(200)
            ->assertJson([
                'data'=>
                    [
                        'id' => $survey->id,
                        'name' => $survey->name,
                        'description' => $survey->description,
                        'key' => $survey->getRouteKey()
                    ]
            ]);
    }

    public function test_controller_show_contains_questions()
    {
        $survey = factory(Survey::class)->create();
        $question = factory(Question::class)->make();
        $survey->questions()->save($question);

        $this->json('get',route('survey.api.survey.show',['survey'=>$survey]))
            ->assertStatus(200)
            ->assertJson([
                'data'=> [
                    'id' => $survey->id,
                    'name' => $survey->name,
                    'description' => $survey->description,
                    'key' => $survey->getRouteKey(),
                    'questions' => [
                        [
                            'id' => $question->id,
                            'question' => $question->question,
                            'type' => $question->type,
                            'required' => $question->required,
                        ]
                    ]
                ]
            ]);
    }

    public function test_controller_show_contains_question_options()
    {
        $survey = factory(Survey::class)->create();
        $question = factory(Question::class)->make(['type'=>'radio']);
        $survey->questions()->save($question);
        $option = factory(Option::class)->make();
        $question->options()->save($option);


        $this->json('get',route('survey.api.survey.show',['survey'=>$survey]))
            ->assertStatus(200)
            ->assertJson([
                'data'=> [
                    'id' => $survey->id,
                    'name' => $survey->name,
                    'description' => $survey->description,
                    'key' => $survey->getRouteKey(),
                    'questions' => [
                        [
                            'id' => $question->id,
                            'question' => $question->question,
                            'type' => $question->type,
                            'required' => $question->required,
                            'order' => $question->order,
                            'options' => [
                                [
                                    'id' => $option->id,
                                    'value' => $option->value,
                                    'order' => $option->order
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
    }

    public function test_guests_can_not_list_all_surveys()
    {
        $this->json('get',route('survey.api.survey.index'))
            ->assertStatus(403);
    }

    public function test_admins_can_list_all_surveys_and_has_pagination_data()
    {
        $survey = factory(Survey::class)->create();
        $survey2 = factory(Survey::class)->create();

        $user = new FakeUser();
        $user->save();
        $this->actingAs($user);

        $this->json('get',route('survey.api.survey.index'))
            ->assertStatus(200)
            ->assertJson([
                'data' =>[
                    [
                        'id' => $survey->id,
                        'name' => $survey->name,
                        'description' => $survey->description,
                        'key' => $survey->getRouteKey(),
                    ],
                    [
                        'id' => $survey2->id,
                        'name' => $survey2->name,
                        'description' => $survey2->description,
                        'key' => $survey2->getRouteKey(),
                    ]
                ],
            ])
            ->assertJsonStructure([
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next',
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'path',
                    'per_page',
                    'to',
                    'total'
                ]
            ]);
    }

    public function test_guests_can_not_delete_surveys()
    {
        $survey = factory(Survey::class)->create();
        $this->json('delete', route('survey.api.survey.destroy',['survey'=>$survey]))
            ->assertStatus(403);

        $this->assertCount(1,Survey::all());
    }

    public function test_admins_can_delete_surveys()
    {
        $survey = factory(Survey::class)->create();

        $user = new FakeUser();
        $user->save();
        $this->actingAs($user);

        $this->json('delete', route('survey.api.survey.destroy',['survey'=>$survey]))
            ->assertStatus(204);

        $this->assertCount(0,Survey::all());
    }

    public function test_guests_can_not_create_surveys()
    {
        $data = [
            'name' => 'Test Survey',
            'description' => 'Test Survey Description'
        ];

        $this->json('post',route('survey.api.survey.store'),$data)
            ->assertStatus(403);
        $this->assertCount(0,Survey::all());
    }

    public function test_admins_can_create_surveys()
    {
        $user = new FakeUser();
        $user->save();
        $this->actingAs($user);

        $data = [
            'name' => 'Test Survey',
            'description' => 'Test Survey Description'
        ];

        $this->json('post',route('survey.api.survey.store'),$data)
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => $data['name'],
                    'description' => $data['description']
                ]
            ]);
        $this->assertCount(1,Survey::all());
    }

    public function test_admins_can_create_surveys_using_uuid_as_key()
    {
        $user = new FakeUser();
        $user->save();
        $this->actingAs($user);

        $data = [
            'name' => 'Test Survey',
            'description' => 'Test Survey Description',
            'key' => 'id'
        ];

        $response = $this->json('post',route('survey.api.survey.store'),$data)
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => $data['name'],
                    'description' => $data['description']
                ]
            ]);

        $this->assertEquals($response->json('data.id'),$response->json('data.key'));
        $this->assertCount(1,Survey::all());
    }

    public function test_admins_can_create_surveys_using_slug_as_key()
    {
        $user = new FakeUser();
        $user->save();
        $this->actingAs($user);

        $data = [
            'name' => 'Test Survey',
            'description' => 'Test Survey Description',
            'key' => 'slug',
            'available_until' => Carbon::now()->addHour()->format(Carbon::ISO8601)
        ];

        $response = $this->json('post',route('survey.api.survey.store'),$data)
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => $data['name'],
                    'description' => $data['description']
                ]
            ]);

        $this->assertEquals($response->json('data.key'),Survey::first()->slug);
        $this->assertEquals($response->json('data.available_until'),$data['available_until']);
        $this->assertCount(1,Survey::all());
    }

    public function test_guests_can_not_edit_surveys()
    {
        $survey = factory(Survey::class)->create();

        $data = [
            'name' => 'Test Survey',
            'description' => 'Test Survey Description'
        ];

        $this->json('put',route('survey.api.survey.update',['survey'=>$survey]),$data)
            ->assertStatus(403);

        $this->assertEquals($survey->name, Survey::first()->name);
        $this->assertEquals($survey->description, Survey::first()->description);
        $this->assertCount(1,Survey::all());
    }

    public function test_admins_can_edit_surveys()
    {
        $user = new FakeUser();
        $user->save();
        $this->actingAs($user);

        $survey = factory(Survey::class)->create();

        $data = [
            'name' => 'Test Survey',
            'description' => 'Test Survey Description'
        ];

        $this->json('put',route('survey.api.survey.update',['survey'=>$survey]),$data)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $survey->id,
                    'name' => $data['name'],
                    'description' => $data['description'],
                ]
            ]);

        $this->assertNotEquals($survey->name, Survey::first()->name);
        $this->assertNotEquals($survey->description, Survey::first()->description);
        $this->assertCount(1,Survey::all());
    }

    public function test_admins_can_see_available_until_and_available_at_properties()
    {
        $user = new FakeUser();
        $user->save();
        $this->actingAs($user);

        $survey = factory(Survey::class)->create(['available_until'=>Carbon::now()->addHour(), 'available_at'=>Carbon::now()]);

        $this->json('get', route('survey.api.survey.show',['survey'=>$survey]))
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'available_at' => $survey->available_at->format(Carbon::ISO8601),
                    'available_until' => $survey->available_until->format(Carbon::ISO8601)
                ]
            ]);
    }

    public function test_admins_can_update_survey_available_until_property()
    {
        $user = new FakeUser();
        $user->save();
        $this->actingAs($user);

        $survey = factory(Survey::class)->create();

        $data = [
            'available_until' => Carbon::now()->addHour()->format(Carbon::ISO8601)
        ];

        $response = $this->json('put',route('survey.api.survey.update',['survey'=>$survey]),$data)
            ->assertStatus(200);

        $this->assertEquals($response->json('data.available_until'),$data['available_until']);

        $this->assertNotNull(Survey::first()->available_until);
        $this->assertCount(1,Survey::all());
    }

    public function test_guests_can_not_see_ended_surveys()
    {
        $survey = factory(Survey::class)->create([
            'available_until' => Carbon::now()->subHour()
        ]);

        $this->json('get',route('survey.api.survey.show',['survey'=>$survey]))
            ->assertStatus(403);
    }

    public function test_guests_can_not_see_unpublished_surveys()
    {
        $survey = factory(Survey::class)->create(['available_at' => null]);

        $this->json('get',route('survey.api.survey.show',['survey'=>$survey]))
            ->assertStatus(403);
    }

    public function test_users_can_not_see_ended_surveys()
    {
        $admin = new FakeUser();
        $admin->save();
        $user = new FakeUser();
        $user->save();
        $this->actingAs($user);

        $survey = factory(Survey::class)->create([
            'available_until' => Carbon::now()->subHour()
        ]);

        $this->json('get',route('survey.api.survey.show',['survey'=>$survey]))
            ->assertStatus(403);
    }

    public function test_users_can_not_see_unpublished_surveys()
    {
        $admin = new FakeUser();
        $admin->save();
        $user = new FakeUser();
        $user->save();
        $this->actingAs($user);

        $survey = factory(Survey::class)->create(['available_at' => null]);

        $this->json('get',route('survey.api.survey.show',['survey'=>$survey]))
            ->assertStatus(403);
    }
}