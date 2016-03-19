<?php

namespace Askedio\Tests\AcceptanceTest;

use Askedio\Tests\AcceptanceTestCase;

class ListTest extends AcceptanceTestCase
{
    public function testList()
    {
        $this->json('GET', '/api/user');
        $response = $this->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
        $this->seeOrSaveJsonStructure();
    }

    public function testSort()
    {
        $this->json('GET', '/api/user?sort=-id');
        $response = $this->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
        $this->seeOrSaveJsonStructure();
    }

    public function testBadSort()
    {
        $this->json('GET', '/api/user?sort=-test');
        $response = $this->response;
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

    public function testListWithFields()
    {
        $this->createUser();

        $this->json('GET', '/api/user?fields[users]=id,name');
        $response = $this->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

    public function testListWithBadFields()
    {
        $this->createUser();

        $this->json('GET', '/api/user?fields[users]=id,name,badtest');
        $response = $this->response;
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

    public function testListWithBadFieldName()
    {
        $this->createUser();

        $this->json('GET', '/api/user?fields[badtest]=id,name,bad');
        $response = $this->response;
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

    public function testSearch()
    {
        $this->json('GET', '/api/user?search=test');
        $response = $this->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

    public function testSearchEmpty()
    {
        $this->json('GET', '/api/user?search=');
        $response = $this->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

    public function testPagination()
    {
        $this->json('GET', '/api/user?page[limit]=1&page[number]=1');
        $response = $this->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

    public function testPaginationBadField()
    {
        $this->json('GET', '/api/user?page[badtest]=1&page[number]=1');
        $response = $this->response;
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }
}
