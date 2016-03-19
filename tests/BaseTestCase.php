<?php

namespace Askedio\Tests;

use Askedio\Tests\App\Profiles;
use Askedio\Tests\App\User;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\ClassFinder;
/* temporary */
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Router;

class BaseTestCase extends \Illuminate\Foundation\Testing\TestCase
{
    use SeeOrSaveJsonStructure;

    /**
     * Setup DB before each test.
     */
    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default', 'sqlite');
        $this->app['config']->set('database.connections.sqlite.database', ':memory:');
        $this->app['config']->set('app.url', 'http://localhost/');
        $this->app['config']->set('app.debug', false);
        $this->app['config']->set('app.key', env('APP_KEY', '1234567890123456'));
        $this->app['config']->set('app.cipher', 'AES-128-CBC');

        $this->app->boot();

        $this->migrate();
    }

    /**
     * run package database migrations.
     */
    public function migrate()
    {
        $fileSystem  = new Filesystem();
        $classFinder = new ClassFinder();

        foreach ($fileSystem->files(__DIR__ . '/App/Database/Migrations') as $file) {
            $fileSystem->requireOnce($file);
            $migrationClass = $classFinder->findClass($file);
            (new $migrationClass())->down();
            (new $migrationClass())->up();
        }
    }

    /**
     * Boots the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        /** @var $app \Illuminate\Foundation\Application */
        $app = require __DIR__ . '/../vendor/laravel/laravel/bootstrap/app.php';

        $this->setUpHttpKernel($app);
        $app->register(\Askedio\Laravel5ApiController\Providers\GenericServiceProvider::class);
        $app->register(\Askedio\Tests\App\Providers\RouteServiceProvider::class);

        return $app;
    }

    /**
     * @return Router
     */
    protected function getRouter()
    {
        $router = new Router(new Dispatcher());

        return $router;
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    private function setUpHttpKernel($app)
    {
        $app->instance('request', (new \Illuminate\Http\Request())->instance());
        $app->make('Illuminate\Foundation\Http\Kernel', [$app, $this->getRouter()])->bootstrap();
    }

    /**
     * Temporary.
     */
    public function createUserRaw()
    {
        /* temporary since we dont have relational creation yet */
        return (new User())->create([
            'name'     => 'test',
            'email'    => 'test@test.com',
            'password' => bcrypt('password'),
        ])->profiles()->saveMany([
            new Profiles(['phone' => '123']),
        ]);
    }
}
