<?php

namespace Askedio\Tests\IntegrationTests;

use Askedio\Tests\App\Profiles;
use Askedio\Tests\App\User;
use Askedio\Tests\App\UserAlt;
use Askedio\Tests\IntegrationTestCase;

class SearchTest extends IntegrationTestCase
{
    public function testSearchWith()
    {
        $search = (new User())->search('test')->with('profiles')->get();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $search);
    }

    public function testSearchGroupBy()
    {
        $search = (new User())->search('test')->with('profiles')->get();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $search);
    }

    public function testSearchNoColumnsDefined()
    {
        $search = (new Profiles())->search('test');
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Builder', $search);
    }

    public function testSearchAltGroupBy()
    {
        $search = (new UserAlt())->search('test')->with('profiles')->get();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $search);
    }
}
