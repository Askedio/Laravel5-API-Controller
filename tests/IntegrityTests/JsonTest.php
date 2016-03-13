<?php

namespace Askedio\Tests\IntegrityTests;

use Askedio\Tests\IntegrityTestCase;

class JsonTest extends IntegrityTestCase
{
    public function testError404()
    {
        $this->json('GET', '/api/404');
        $response = $this->response;
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

    public function testError404User()
    {
        $this->json('GET', '/api/user/404');
        $response = $this->response;
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

    public function testBadQueryVar()
    {
        $this->json('GET', '/api/user/?badtest');
        $response = $this->response;
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

    public function testNonApiException()
    {
        $this->json('GET', '/not-the-api');
        $response = $this->response;
        $this->assertEquals(404, $response->getStatusCode());
    }
}
