<?php


namespace Sniper7Kills\Survey\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Sniper7Kills\Survey\Tests\Feature\SurveyAdminTestFakeUser;
use Sniper7Kills\Survey\Tests\TestCase;

class SurveyIncludedMiddlewareTest extends TestCase
{
    public function test_included_middleware_allows_user_id_1()
    {
        $admin = new SurveyIncludedMiddlewareTestFakeUser();
        $admin->save();

        $this->actingAs($admin);

        $root_path = Config::get('survey.root_path');
        $this->get('/'.$root_path.'/admin/dashboard')
            ->assertStatus(200);
    }

    public function test_included_middleware_denies_user_id_2()
    {
        $admin = new SurveyIncludedMiddlewareTestFakeUser();
        $admin->save();

        $user = new SurveyIncludedMiddlewareTestFakeUser();
        $user->id = 2;
        $user->save();

        $this->actingAs($user);

        $root_path = Config::get('survey.root_path');
        $this->get('/'.$root_path.'/admin/dashboard')
            ->assertStatus(403);
    }

    public function test_included_middleware_denies_guest()
    {
        $root_path = Config::get('survey.root_path','survey');
        $this->get('/'.$root_path.'/admin/dashboard')
            ->assertStatus(403);
    }
}
class SurveyIncludedMiddlewareTestFakeUser extends Model implements \Illuminate\Contracts\Auth\Authenticatable
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