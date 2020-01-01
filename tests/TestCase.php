<?php


namespace Sniper7Kills\Survey\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as OTestCast;


class TestCase extends OTestCast
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadLaravelMigrations(['--database' => 'testbench']);
        $this->artisan('migrate', ['--database' => 'testbench'])->run();

        $this->withFactories(__DIR__ . '/../database/factories');

        Schema::create('test-users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    protected function getPackageProviders($app)
    {
        return ['Sniper7Kills\Survey\SurveyServiceProvider'];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    public function test_assert_true()
    {
        $this->assertTrue(true);
    }
}