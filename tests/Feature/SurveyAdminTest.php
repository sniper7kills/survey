<?php


namespace Sniper7Kills\Survey\Tests\Feature;


use Illuminate\Database\Eloquent\Model;
use Sniper7Kills\Survey\Tests\TestCase;

class SurveyAdminTest extends TestCase
{

}

class SurveyAdminTestFakeUser extends Model implements \Illuminate\Contracts\Auth\Authenticatable
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