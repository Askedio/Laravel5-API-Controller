<?php

namespace Askedio\Tests\AcceptanceTest;

use Askedio\Tests\AcceptanceTestCase;

class CrudTest extends AcceptanceTestCase
{
    public function testCreate()
    {
        $results = $this->createUser();
        $response = $results->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

    public function testRead()
    {
        $this->createUser();
        $this->json('GET', '/api/user/1');
        $response = $this->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
        $this->seeOrSaveJsonStructure($response);
    }

    public function testUpdate()
    {
        $this->createUser();
        $this->json('PATCH', '/api/user/1', [
          'name' => 'testupdate',
          ]);
        $response = $this->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
        $this->seeJson(['name' => 'testupdate']);
    }

    public function testDelete()
    {
        $this->createUser();

        $this->json('DELETE', '/api/user/1');
        $response = $this->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }
}
