<?php

namespace Askedio\Tests;

class JsonTest extends ApiCase
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

    public function testError404()
    {
        $this->json('GET', '/api/404');
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

    public function testBadInclude()
    {
        $this->json('GET', '/api/user/?include=badtest');
        $response = $this->response;
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

    public function testInclude()
    {
        $this->json('GET', '/api/user/?include=profiles');
        $response = $this->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

    public function testSearch()
    {
        $this->json('GET', '/api/user/?search=test');
        $response = $this->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

    public function testNonApiException()
    {
        $this->json('GET', '/not-the-api');
        $response = $this->response;
        $this->assertEquals(404, $response->getStatusCode());
    }

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
