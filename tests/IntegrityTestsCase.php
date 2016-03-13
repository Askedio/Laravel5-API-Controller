<?php

namespace Askedio\Tests;

use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\ClassFinder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Routing\Router;

class IntegrityTestsCase extends \Illuminate\Foundation\Testing\TestCase
{
    //use WithoutMiddleware;

// need to split this out noiw.
    /** @var string */
    public $baseUrl = 'http://localhost';

    /** @var string */
    public $read = '{"data":{"type":"user","id":50,"attributes":{"id":50,"name":"uyooewew","email":"uy@asd.copm","created_at":"2016-03-10 17:40:46","updated_at":"2016-03-11 20:45:18"}},"jsonapi":{"version":"1.0","self":"v1"}}';

    /** @var string */
    public $list = '{"data":[],"meta":{"total":0,"currentPage":1,"perPage":"10","hasMorePages":false,"hasPages":false},"links":{"self":"http:\/\/localhost\/api\/user?page=1","first":"http:\/\/localhost\/api\/user?page=1","last":"http:\/\/localhost\/api\/user?page=1","next":null,"prev":null},"jsonapi":{"version":"1.0","self":"v1"}}';

    public function json($method, $uri, array $data = [], array $headers = [])
    {
        $headers = array_merge($headers, array_merge(['Content-Type' => config('jsonapi.content_type'), 'Accept' => config('jsonapi.accept')], $headers));

        return parent::json($method, $uri, $data, $headers);
    }

    /**
     * Setup DB before each test.
     */
    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('database.default', 'sqlite');
        $this->app['config']->set('database.connections.sqlite.database', ':memory:');
        $this->app['config']->set('app.url', 'http://localhost/');
        $this->app['config']->set('app.debug', true);
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
        $fileSystem = new Filesystem();
        $classFinder = new ClassFinder();

        foreach ($fileSystem->files(__DIR__.'/App/Database/Migrations') as $file) {
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
        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';

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
     * Return an array of keys for json validation.
     *
     * @param array $array
     *
     * @return array
     */
    public function arrayKeys($array)
    {
        $results = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $results = array_merge($results, [$key => array_merge(array_keys($value), $this->arrayKeys($value))]);
            }
        }

        return $results;
    }

    /**
     * Get Keys from a json array.
     *
     * @param string $var
     *
     * @return array
     */
    public function getKeys($var)
    {
        return $this->arrayKeys(json_decode($var, true));
    }

    /**
     * Create User Helpers.
     *
     * @return json
     */
    public function createUser()
    {
        $this->json('POST', '/api/user', [
          'name'     => 'test',
          'email'    => 'test@test.com',
          'password' => bcrypt('password'), ]);
    }

    public function saveOutput($response)
    {
        print_r($response->getContent());
        exit;
    }
}
