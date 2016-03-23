<?php

namespace Askedio\Tests\AcceptanceTest;

use Askedio\Tests\AcceptanceTestCase;

class AuthTest extends AcceptanceTestCase
{
    public function testRead()
    {
        $this->createUserRaw();
        $this->json('GET', '/api/me/profile', [], ['Authorization' => 'Basic YWRtaW5AbG9jYWxob3N0LmNvbTpwYXNzd29yZA==']);
        $response = $this->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
        $this->seeOrSaveJsonStructure($response);
    }

    public function testBadAuth()
    {
        $this->createUserRaw();
        $this->json('GET', '/api/me/profile', [], ['Authorization' => 'Basic ZWRtaW5AbG9jYWxob3N0LmNvbTpwYXNzd29yZA==']);
        $response = $this->response;

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }
}
