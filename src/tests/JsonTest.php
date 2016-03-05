<?php

namespace Askedio\Laravel5ApiController\Tests;


class JsonTest extends TestCase
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
        $this->json('GET', '/api/user/1')
             ->seeJson([
                 'success' => true,
             ]);

  }
  

    public function testUpdate()
  {
        $this->createUser();
        $this->json('PATCH', '/api/user/1', [
          'name' => 'testupdate',
          ])
             ->seeJson([
                 'success' => true,
             ]);

  }
  
  public function testDelete()
    {

        $this->createUser();

        $this->json('DELETE', '/api/user/1')
             ->seeJson([
                 'success' => true,
             ]);
    }

    public function testList()
    {

        $this->createUser();

        $this->json('GET', '/api/user')
             ->seeJsonStructure([
                 'results' => ['total'],
             ]);
    }



    private function createUser()
  {
        $this->json('POST', '/api/user', [
          'name' => 'test',
          'email' => 'test@test.com',
          'password' => bcrypt('password')])
             ->seeJson([
                 'success' => true,
             ]);

  }

}