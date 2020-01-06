<?php


namespace Sniper7Kills\Survey\Tests\Feature\Api;


use Sniper7Kills\Survey\Models\Survey;
use Sniper7Kills\Survey\Tests\FakeUser;
use Sniper7Kills\Survey\Tests\TestCase;

class PublishedSurveyControllerTest extends TestCase
{
    public function test_guests_can_not_publish_survey()
    {
        $survey = factory(Survey::class)->create(['available_at'=>null]);

        $data = [
            'survey_id' => $survey->id
        ];

        $this->json('post', route('survey.api.publishedSurvey.store'), $data)
            ->assertStatus(403);
    }

    public function test_guests_can_not_unpublish_survey()
    {
        $survey = factory(Survey::class)->create(['available_at'=>null]);

        $data = [
            'survey_id' => $survey->id
        ];

        $this->json('delete', route('survey.api.publishedSurvey.destroy',['publishedSurvey'=>$survey]))
            ->assertStatus(403);
    }

    public function test_users_can_not_publish_survey()
    {
        $admin = new FakeUser();
        $admin->save();
        $user = new FakeUser();
        $user->save();
        $this->actingAs($user);

        $survey = factory(Survey::class)->create(['available_at'=>null]);

        $data = [
            'survey_id' => $survey->id
        ];

        $this->json('post', route('survey.api.publishedSurvey.store'), $data)
            ->assertStatus(403);
    }

    public function test_users_can_not_unpublish_survey()
    {
        $admin = new FakeUser();
        $admin->save();
        $user = new FakeUser();
        $user->save();
        $this->actingAs($user);

        $survey = factory(Survey::class)->create(['available_at'=>null]);

        $data = [
            'survey_id' => $survey->id
        ];

        $this->json('delete', route('survey.api.publishedSurvey.destroy',['publishedSurvey'=>$survey]))
            ->assertStatus(403);
    }

    public function test_admins_can_publish_survey()
    {
        $admin = new FakeUser();
        $admin->save();
        $this->actingAs($admin);

        $survey = factory(Survey::class)->create(['available_at'=>null]);
        $data = [
            'survey_id' => $survey->id
        ];

        $this->json('post', route('survey.api.publishedSurvey.store'), $data)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $survey->id,
                    'name' => $survey->name,
                    'description' => $survey->description
                ]
            ]);

        $this->assertNotNull(Survey::first()->available_at);
    }

    public function test_admins_can_unpublish_survey()
    {
        $admin = new FakeUser();
        $admin->save();
        $this->actingAs($admin);

        $survey = factory(Survey::class)->create();

        $this->json('delete', route('survey.api.publishedSurvey.destroy',['publishedSurvey'=>$survey]))
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $survey->id,
                    'name' => $survey->name,
                    'description' => $survey->description
                ]
            ]);

        $this->assertNull(Survey::first()->available_at);
    }
}