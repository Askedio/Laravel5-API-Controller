<?php

namespace Askedio\Tests;

use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\ClassFinder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Routing\Router;

class ApiCase extends \Illuminate\Foundation\Testing\TestCase
{
    use WithoutMiddleware;

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
        $this->app['config']->set('app.key', \env('APP_KEY', '1234567890123456'));
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
     * This is required for \Symfony\Bridge\PsrHttpMessage\Factory to work.
     * This comes as a trade-off of building the underlying package as framework-agnostic.
     *
     * @param string $method
     * @param string $uri
     * @param array  $parameters
     * @param array  $cookies
     * @param array  $files
     * @param array  $server
     * @param string $content
     *
     * @return \Illuminate\Http\Response
     */
    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        $_SERVER['SERVER_NAME'] = parse_url($uri, PHP_URL_HOST);
        $_SERVER['REQUEST_URI'] = str_replace([parse_url($uri, PHP_URL_HOST), parse_url($uri, PHP_URL_SCHEME).'://'], '', $uri);
        $_SERVER['REQUEST_METHOD'] = strtoupper($method);
        $_SERVER['QUERY_STRING'] = parse_url($uri, PHP_URL_QUERY);
        $_SERVER['PATH_INFO'] = str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
        $_SERVER['CONTENT_TYPE'] = 'application/json';
        $_SERVER['argv'] = explode('&', $_SERVER['QUERY_STRING']);
        parse_str($_SERVER['QUERY_STRING'], $_GET);

        return parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }
}
