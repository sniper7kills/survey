<?php


namespace Sniper7Kills\Survey\Tests;


use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Sniper7Kills\Survey\Contract\SurveyAdmin;

class FakeUser extends Model implements AuthenticatableContract, SurveyAdmin
{
    use Authenticatable;

    protected $table = 'test-users';

    public function isASurveyAdmin()
    {
        if($this->id == 1)
            return true;
        return false;
    }
}