<?php

namespace Sniper7Kills\Survey\Tests\Unit;

use Sniper7Kills\Survey\Models\Option;
use Sniper7Kills\Survey\Models\Question;
use Sniper7Kills\Survey\Models\Response;
use Sniper7Kills\Survey\Models\Survey;
use Sniper7Kills\Survey\Tests\FakeUser;
use Sniper7Kills\Survey\Tests\TestCase;

class ResponseModelTest extends TestCase
{
    public function test_question_has_id_on_create()
    {
        $survey = factory(Survey::class)->create();

        $user = new FakeUser();
        $user->save();
        $response = factory(Response::class)->make();
        $response->userable()->associate($user);
        $survey->responses()->save($response);

        $this->assertNotNull($response->id);
    }
}