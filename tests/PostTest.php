<?php

namespace Askedio\Tests;

class PostTest extends ApiCase
{
    public function testBadPostField()
    {
        $this->json('POST', '/api/user', [
        'test'     => 'test',
      ]);

        $response = $this->response;
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

    public function testBadPatchField()
    {
        $this->json('PATCH', '/api/user', [
            'test'     => 'test',
          ]);

        $response = $this->response;
        $this->assertEquals(405, $response->getStatusCode());
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

    public function testPatchValidation()
    {
        $this->json('PATCH', '/api/user', [
        'email'     => 'test',
      ]);

        $response = $this->response;
        $this->assertEquals(405, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }
}
