<?php

namespace Askedio\Tests\AcceptanceTest;

use Askedio\Tests\AcceptanceTestCase;

class PatchTest extends AcceptanceTestCase
{
    public function testBadPatchField()
    {
      $this->createUser();
        $this->json('PATCH', '/api/user/1', [
            'test'     => 'test',
          ]);

        $response = $this->response;
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

    public function testPatchValidation()
    {
      $this->createUser();
        $this->json('PATCH', '/api/user/1', [
        'email'     => 'test',
      ]);

        $response = $this->response;
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }


    public function testPatch404()
    {
        $this->json('PATCH', '/api/user/404', [
        'email'     => 'test@test.com',
      ]);

        $response = $this->response;
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

}
