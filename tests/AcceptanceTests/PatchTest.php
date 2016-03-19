<?php

namespace Askedio\Tests\AcceptanceTest;

use Askedio\Tests\AcceptanceTestCase;

class PatchTest extends AcceptanceTestCase
{
    public function testUpdate()
    {
        $this->createUser();
        $this->json('PATCH', '/api/user/1', [
            'data' => [
                'type'       => 'users',
                'attributes' => [
                    'name' => 'testupdate',
                ],
            ],
        ]);
        $response = $this->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
        $this->seeJson(['name' => 'testupdate']);
    }

    public function testBadPatchField()
    {
        $this->createUser();
        $this->json('PATCH', '/api/user/1', [
            'test' => 'test',
        ]);

        $response = $this->response;
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

    public function testPatchValidation()
    {
        $this->createUser();
        $this->json('PATCH', '/api/user/1', [
            'data' => [
                'type'       => 'users',
                'attributes' => [
                    'email' => 'notanemail',
                ],
            ],
        ]);

        $response = $this->response;
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

    public function testPatch404()
    {
        $this->json('PATCH', '/api/user/404', [
            'data' => [
                'type'       => 'users',
                'attributes' => [
                    'name' => 'Ember Hamster kpok',
                ],
            ],
        ]);

        $response = $this->response;
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }
}
