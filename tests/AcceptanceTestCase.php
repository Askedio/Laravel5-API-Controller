<?php

namespace Askedio\Tests;

class AcceptanceTestCase extends BaseTestCase
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
        return $this->json('POST', '/api/user', [
          'name'     => 'test',
          'email'    => 'test@test.com',
          'password' => bcrypt('password'), ]);
    }

    public function saveOutput($response)
    {
        /*
          TO-DO: save to config file and use said config file to load check arrays from, only when triggered to do so (like maybe some cli flag)
       */
        dd($response->getContent());
    }
}
