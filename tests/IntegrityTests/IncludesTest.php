<?php

namespace Askedio\Tests;

class IncludesTest extends IntegrityTestsCase
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
        $this->json('GET', '/api/user/?include=profiles');
        $response = $this->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }
}
