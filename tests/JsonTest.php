<?php

namespace Askedio\Tests;

class JsonTest extends ApiCase
{
    public $baseUrl = 'http://localhost';

    public function setUp()
    {
        parent::setUp();
    }

    public function testCreate()
    {
        $this->createUser();
    }

    public function testRead()
    {
        $this->createUser();
        $this->json('GET', '/api/user/1');
    }

    public function testUpdate()
    {
        $this->createUser();
        $this->json('PATCH', '/api/user/1', [
          'name' => 'testupdate',
          ]);
    }

    public function testDelete()
    {
        $this->createUser();

        $this->json('DELETE', '/api/user/1');
    }

    public function testList()
    {
        $this->createUser();
        $this->json('GET', '/api/user');
    }

    private function createUser()
    {
        $this->json('POST', '/api/user', [
          'name'     => 'test',
          'email'    => 'test@test.com',
          'password' => bcrypt('password'), ]);
    }
}
