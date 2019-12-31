<?php


namespace Sniper7Kills\Survey\Tests\Unit;


use Sniper7Kills\Survey\Models\Survey;
use Sniper7Kills\Survey\Tests\TestCase;

class SurveyTests extends TestCase
{
    public function test_surveys_can_not_have_same_slug()
    {
        $survey1 = new Survey(['name'=>'Test Survey','description'=>'Test Survey']);
        $survey1->save();

        $survey2 = new Survey(['name'=>'Test Survey','description'=>'Test Survey']);
        $survey2->save();

        $this->assertNotEquals($survey1->slug, $survey2->slug);
    }
}