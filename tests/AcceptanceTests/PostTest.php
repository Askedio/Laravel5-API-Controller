<?php

namespace Askedio\Tests\AcceptanceTest;

use Askedio\Tests\AcceptanceTestCase;

class PostTest extends AcceptanceTestCase
{
    public function testBadPostField()
    {
        $this->json('POST', '/api/user', [
        'test'     => 'test',
      ]);

        $response = $this->response;
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

    public function testPostValidation()
    {
        $this->json('POST', '/api/user', [
        'email'     => 'test',
      ]);

        $response = $this->response;
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }
}
