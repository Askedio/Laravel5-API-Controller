<?php

namespace Askedio\Tests;

class AcceptanceTestCase extends BaseTestCase
{
    /** @var string */
    public $baseUrl = 'http://localhost';

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
          'data' => [
            'type'       => 'users',
            'attributes' => [
              'name'     => 'Ember Hamster',
              'email'    => 'test@test.com',
              'password' => bcrypt('password'),
            ],
          ],
        ]);
    }
}
