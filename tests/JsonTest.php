<?php

namespace Askedio\Tests;

class JsonTest extends ApiCase
{
    /** @var string */
    public $baseUrl = 'http://localhost';

    /** @var string */
    private $read = '{"data":{"type":"user","id":50,"attributes":{"id":50,"name":"uyooewew","email":"uy@asd.copm","created_at":"2016-03-10 17:40:46","updated_at":"2016-03-11 20:45:18"}},"jsonapi":{"version":"1.0","self":"v1"}}';

    /** @var string */
    private $list = '{"data":[],"meta":{"total":0,"currentPage":1,"perPage":"10","hasMorePages":false,"hasPages":false},"links":{"self":"http:\/\/localhost\/api\/user?page=1","first":"http:\/\/localhost\/api\/user?page=1","last":"http:\/\/localhost\/api\/user?page=1","next":null,"prev":null},"jsonapi":{"version":"1.0","self":"v1"}}';

    public function testRead()
    {
        $this->createUser();
        $this->json('GET', '/api/user/1');
        $response = $this->response;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
        $this->seeJsonStructure($this->getKeys($this->read));
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
        $this->seeJsonStructure($this->getKeys($this->read));
    }

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

                $this->json('GET', '/api/user?fields[user]=id,name,bad');
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
        $this->json('GET', '/api/user/?badvar');
        $response = $this->response;
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }

    public function testBadInclude()
    {
        $this->json('GET', '/api/user/?includes=test');
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

    /*

    TO-DO: no include var = no error,
    public function testBadIncludes()
    {
        $this->json('GET', '/api/user/?include=test');
        $response = $this->response;
        print_r($response);exit;
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(config('jsonapi.content_type'), $response->headers->get('Content-type'));
    }
    */
    // test good filters
    // test good includes
    // test good sort

    //
    // invalid_attribute
    // invalid_get

    // invalid_sort
    // unsupported-media-type
    // not-acceptable
    // not_found
    //
    // test search

    private function createUser()
    {
        $this->json('POST', '/api/user', [
          'name'     => 'test',
          'email'    => 'test@test.com',
          'password' => bcrypt('password'), ]);
    }
}
