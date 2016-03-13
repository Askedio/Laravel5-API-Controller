<?php

namespace Askedio\Tests;
use Askedio\Tests\App\User;
use Askedio\Tests\App\Profiles;
use Askedio\Laravel5ApiController\Helpers\ApiController;
class UnitTestCase extends BaseTestCase
{

  public function api()
  {
    return new ApiController(new User());
  }

  public function createUser()
  {
    /** temporary since we dont have relational creation yet */
    return $this->createUserRaw();
  }


}
