<?php

namespace Askedio\Tests\IntegrityTests;
use Askedio\Tests\IntegrityTestCase;
class HeadersTest extends IntegrityTestCase
{
    public function testBadContentType()
    {
        $this->json('GET', '/api/user/', [], ['Content-Type' => 'test']);
        $response = $this->response;
        $this->assertEquals(415, $response->getStatusCode());
    }

    public function testBadContentAccept()
    {
        $this->json('GET', '/api/user/', [], ['Accept' => 'test']);
        $response = $this->response;
        $this->assertEquals(406, $response->getStatusCode());
    }

    public function testVersionContentType()
    {
        $this->json('GET', '/api/user/', [], ['Accept' => 'application/vnd.api.v1+json']);
        $response = $this->response;
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testBadVersion()
    {
        $this->json('GET', '/api/user/', [], ['Accept' => 'application/vnd.api.v2+json']);
        $response = $this->response;
        $this->assertEquals(406, $response->getStatusCode());
    }
}
