<?php

namespace Askedio\Tests;

class ListTest extends IntegrityTestsCase
{
    public function testList()
    {
        $this->json('GET', '/api/user');
        $response = $this->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
        $this->seeJsonStructure($this->getKeys($this->list));
    }

    public function testSort()
    {
        $this->json('GET', '/api/user?sort=-id');
        $response = $this->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
        $this->seeJsonStructure($this->getKeys($this->list));
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

        $this->json('GET', '/api/user?fields[user]=id,name');
        $response = $this->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

    public function testListWithBadFields()
    {
        $this->createUser();

        $this->json('GET', '/api/user?fields[user]=id,name,badtest');
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
        $this->json('GET', '/api/user/?search=test');
        $response = $this->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }
}
