<?php

namespace Askedio\Tests\IntegrationTests;

use Askedio\Tests\IntegrationTestCase;
use Askedio\Tests\App\User;
use Askedio\Tests\App\UserAlt;
use Askedio\Tests\App\Profiles;

class SearchTest extends IntegrationTestCase
{
    public function testSearchWith()
    {
      $search = User::search('test')->with('profiles')->get();
    }

    public function testSearchGroupBy()
    {
      $search = User::search('test')->with('profiles')->get();
    }

    public function testSearchEntireText()
    {
      $search = User::search('test', 10, true)->with('profiles')->get();
    }

    public function testSearchNoColumnsDefined()
    {
      $search = Profiles::search('test');
    }

    public function testSearchAltGroupBy()
    {
      $search = UserAlt::search('test')->with('profiles')->get();
    }

}
