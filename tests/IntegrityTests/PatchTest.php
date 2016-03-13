<?php

namespace Askedio\Tests\IntegrityTests;

use Askedio\Tests\IntegrityTestCase;

class PatchTest extends IntegrityTestCase
{
    public function testBadPatchField()
    {
        $this->json('PATCH', '/api/user/1', [
            'test'     => 'test',
          ]);

        $response = $this->response;
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

    public function testPatchValidation()
    {
        $this->json('PATCH', '/api/user/1', [
        'email'     => 'test',
      ]);

        $response = $this->response;
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }
}
