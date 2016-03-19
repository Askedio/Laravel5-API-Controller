<?php

namespace Askedio\Tests;

use Askedio\Laravel5ApiController\Helpers\ApiController;
use Askedio\Tests\App\User;

class UnitTestCase extends BaseTestCase
{
   public $baseUrl = 'http://localhost';

    public function api()
    {
        return new ApiController(new User());
    }

    public function createUser()
    {
        /* temporary since we dont have relational creation yet */
        return $this->createUserRaw();
    }
}
