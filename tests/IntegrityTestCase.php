<?php

namespace Askedio\Tests;

use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\ClassFinder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Routing\Router;
use Artisan;

class IntegrityTestCase extends BaseTestCase
{

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
        print_r($response->getContent());exit;
    }
}