<?php

namespace Askedio\Tests\AcceptanceTest;

use Askedio\Tests\AcceptanceTestCase;

class IncludesTest extends AcceptanceTestCase
{
    public function testBadInclude()
    {
        $this->json('GET', '/api/user/?include=badtest');
        $response = $this->response;
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

    public function testInclude()
    {
        $this->createUserRaw();
        $this->json('GET', '/api/user/?include=profiles');
        $response = $this->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

    public function testListWithIncludeFields()
    {
        $this->createUserRaw();

        $this->json('GET', '/api/user?fields[profiles]=id,phone');
        $response = $this->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }
}
